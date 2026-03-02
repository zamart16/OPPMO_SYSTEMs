<?php

namespace App\Http\Controllers;

use App\Models\CriteriaScore;
use App\Models\DigitalApproval;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationLink;

class EvaluationController extends Controller
{
public function store(Request $request)
{
    DB::beginTransaction();

    try {
        foreach ($request->evaluations as $evalData) {

            // Skip duplicate check if PO is null or empty
            $poNo = $evalData['po_no'] ?? null;
            if (!empty($poNo) && Evaluation::where('po_no', $poNo)->exists()) {
                throw new \Exception("Duplicate PO number detected: {$poNo}.");
            }

            // Safely parse date_evaluation
            $dateEvaluation = null;
            if (!empty($evalData['date_evaluation'])) {
                try {
                    $dateEvaluation = Carbon::parse($evalData['date_evaluation'])->timezone('Asia/Manila');
                } catch (\Exception $ex) {
                    $dateEvaluation = null; // fallback to null
                }
            }

            // Create evaluation
            $evaluation = Evaluation::create([
                'supplier_name'   => $evalData['supplier_name'] ?? null,
                'po_no'           => $poNo,
                'date_evaluation' => $dateEvaluation,
                'covered_period'  => $evalData['covered_period'] ?? null,
                'office_name'     => $evalData['office_name'] ?? null,
            ]);

            // ===============================
            // CRITERIA SCORES
            // ===============================
            $criteriaIds = [1, 2, 3, 4];
            foreach ($criteriaIds as $criteriaId) {
                $criteriaData = collect($evalData['criteria'] ?? [])
                    ->firstWhere('criteria_id', $criteriaId);

                CriteriaScore::create([
                    'evaluation_id' => $evaluation->id,
                    'criteria_id'   => $criteriaId,
                    'number_rating' => $criteriaData['rating'] ?? null,
                    'remarks'       => $criteriaData['remarks'] ?? null,
                ]);
            }

            // ===============================
            // DIGITAL APPROVAL (Evaluator)
            // ===============================
            $evaluator = $request->evaluator ?? null;
            if (!empty($evaluator)) {
                $imageUrl = null;

                // Handle Base64 image if provided
                if (!empty($evaluator['image']) && str_contains($evaluator['image'], 'data:image')) {
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $evaluator['image']));
                    if ($imageData !== false) {
                        $fileName = 'evaluation_signatures/' . Str::uuid() . '.png';

                        // Supabase storage example
                        $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
                        $supabaseKey = env('SUPABASE_SERVICE_ROLE_KEY');
                        $bucket = 'image';

                        $response = Http::withHeaders([
                            'apikey'        => $supabaseKey,
                            'Authorization' => 'Bearer ' . $supabaseKey,
                            'Content-Type'  => 'image/png',
                        ])->withBody($imageData, 'image/png')
                          ->put("$supabaseUrl/storage/v1/object/$bucket/$fileName");

                        if ($response->successful()) {
                            $imageUrl = "$supabaseUrl/storage/v1/object/public/$bucket/$fileName";
                        }
                    }
                }

                DigitalApproval::create([
                    'evaluation_id' => $evaluation->id,
                    'full_name'     => $evaluator['name'] ?? null,
                    'designation'   => $evaluator['designation'] ?? null,
                    'role'          => 'Prepared By',
                    'image'         => $imageUrl, // store uploaded image URL
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Evaluation saved successfully!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error during evaluation save', [
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        $message = env('APP_DEBUG') ? $e->getMessage() : 'An unexpected error occurred while saving the evaluation.';

        return response()->json([
            'success' => false,
            'message' => $message
        ], 500);
    }
}


public function list()
{
    try {
        $user = auth()->user();
        $userDepartment = $user->department ?? null;
        $userRole = strtolower($user->role ?? '');

        $query = Evaluation::with(['criteriaScores', 'digitalApprovals'])
            ->orderBy('date_evaluation', 'asc');

        if ($userRole !== 'administrator') {
            // Non-admins see only their department
            $query->where('office_name', $userDepartment);
        }

        $evaluations = $query->get();

        $result = $evaluations->map(function ($eval) {

            $criteriaScores = [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
            ];

            foreach ($eval->criteriaScores as $score) {
                $criteriaScores[$score->criteria_id] = $score->number_rating ?? 0;
            }

            // Weighted total
            $poScore = (5 * $criteriaScores[1]) +
                       (7.5 * $criteriaScores[2]) +
                       (6.25 * $criteriaScores[3]) +
                       (6.25 * $criteriaScores[4]);

            // Head approval check
            $headApproval = $eval->digitalApprovals
                ->firstWhere('role', 'Head');

            $status = !$headApproval ? 'For HEAD REVIEW' : ($poScore >= 60 ? 'Approved' : 'Fail!');

            // Evaluator (Prepared By)
            $evaluator = $eval->digitalApprovals
                ->firstWhere('role', 'Prepared By');

            return [
                'id' => $eval->id,
                'supplier_name' => $eval->supplier_name ?? 'N/A',
                'po_no' => $eval->po_no ?? 'N/A',
                'date_evaluation' => $eval->date_evaluation ?? 'N/A',
                'department' => $eval->office_name ?? 'N/A',
                'eval_score' => round($poScore, 2),
                'status' => $status,
                'evaluator' => $evaluator ? $evaluator->full_name : 'N/A',
                'criteria_scores' => $eval->criteriaScores,
                'digital_approvals' => $eval->digitalApprovals,
            ];
        });

        return response()->json([
            'evaluations' => $result
        ]);

    } catch (\Exception $e) {
        Log::error('Evaluation list fetch failed', ['error' => $e->getMessage()]);
        return response()->json([
            'message' => 'Failed to fetch evaluations: ' . $e->getMessage()
        ], 500);
    }
}

// Delete evaluation remains the same
public function destroy($id)
{
    $evaluation = Evaluation::findOrFail($id);
    $evaluation->criteriaScores()->delete();
    $evaluation->digitalApprovals()->delete();
    $evaluation->delete();

    return response()->json([
        'success' => true,
        'message' => 'Evaluation deleted successfully.'
    ]);
}

public function show($id)
{
    $evaluation = Evaluation::with('criteriaScores', 'digitalApprovals')->findOrFail($id);

    $evaluator = $evaluation->digitalApprovals->first();

    return response()->json([
        'evaluation' => $evaluation,
        'evaluator' => $evaluator
    ]);
}







public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $evaluation = Evaluation::findOrFail($id);

        // ===============================
        // UPDATE BASIC INFO
        // ===============================
        $evaluation->update([
            'supplier_name'   => $request->supplier_name ?? $evaluation->supplier_name,
            'po_no'           => $request->po_no ?? $evaluation->po_no,
            'date_evaluation' => !empty($request->date_evaluation)
                                    ? Carbon::parse($request->date_evaluation)->timezone('Asia/Manila')
                                    : $evaluation->date_evaluation,
            'covered_period'  => $request->covered_period ?? $evaluation->covered_period,
            'office_name'     => $request->office_name ?? $evaluation->office_name,
        ]);

        // ===============================
        // UPDATE CRITERIA SCORES
        // ===============================
        $criteriaScores = $request->criteria_scores ?? [];
        foreach ($criteriaScores as $scoreData) {
            $criteriaScore = CriteriaScore::firstOrNew([
                'evaluation_id' => $evaluation->id,
                'criteria_id'   => $scoreData['criteria_id']
            ]);

            $criteriaScore->number_rating = $scoreData['number_rating'] ?? $criteriaScore->number_rating;
            $criteriaScore->remarks       = $scoreData['remarks'] ?? $criteriaScore->remarks;
            $criteriaScore->save();
        }

        // ===============================
        // UPDATE DIGITAL APPROVAL (Prepared By)
        // ===============================
        if (!empty($request->evaluator)) {
            $digitalApproval = DigitalApproval::firstOrNew([
                'evaluation_id' => $evaluation->id,
                'role' => 'Prepared By'
            ]);

            $digitalApproval->full_name   = $request->evaluator['full_name'] ?? $digitalApproval->full_name;
            $digitalApproval->designation = $request->evaluator['designation'] ?? $digitalApproval->designation;

            // Process Base64 image if provided
            if (!empty($request->evaluator['image']) &&
                str_contains($request->evaluator['image'], 'data:image')) {

                $imageData = base64_decode(
                    preg_replace('#^data:image/\w+;base64,#i', '', $request->evaluator['image'])
                );

                if ($imageData !== false) {

                    $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
                    $supabaseKey = env('SUPABASE_SERVICE_ROLE_KEY');
                    $bucket = 'image';

                    // Delete old image if exists
                    if (!empty($digitalApproval->image) &&
                        str_contains($digitalApproval->image, "$supabaseUrl/storage/v1/object/public/$bucket/")) {

                        $oldPath = str_replace(
                            "$supabaseUrl/storage/v1/object/public/$bucket/",
                            '',
                            $digitalApproval->image
                        );

                        try {
                            Http::timeout(5)
                                ->withHeaders([
                                    'apikey'        => $supabaseKey,
                                    'Authorization' => 'Bearer ' . $supabaseKey,
                                ])
                                ->delete("$supabaseUrl/storage/v1/object/$bucket/$oldPath");
                        } catch (\Exception $ex) {
                            Log::warning("Could not delete old evaluator image: " . $ex->getMessage());
                        }
                    }

                    // Upload new image
                    $fileName = 'evaluation_signatures/' . Str::uuid() . '.png';

                    try {
                        $response = Http::timeout(10)
                            ->withHeaders([
                                'apikey'        => $supabaseKey,
                                'Authorization' => 'Bearer ' . $supabaseKey,
                                'Content-Type'  => 'image/png',
                            ])
                            ->withBody($imageData, 'image/png')
                            ->put("$supabaseUrl/storage/v1/object/$bucket/$fileName");

                        if ($response->successful()) {
                            $digitalApproval->image =
                                "$supabaseUrl/storage/v1/object/public/$bucket/$fileName";
                        }
                    } catch (\Exception $ex) {
                        Log::warning("Could not upload evaluator image: " . $ex->getMessage());
                    }
                }
            }

            $digitalApproval->save();
        }

        // ===============================
        // CREATE OR RETURN EXISTING HEAD REVIEW LINK
        // ===============================
        $link = EvaluationLink::firstOrCreate(
            [
                'evaluation_id' => $evaluation->id,
                'reviewer_role' => 'Head'
            ],
            [
                'token'        => Str::uuid()->toString(),
                'expires_at'   => null, // never expires
                'is_completed' => false,
            ]
        );

        // Build Head Review URL
        $headReviewUrl = url('/evaluation/head-review/' . $link->token);

        DB::commit();

        return response()->json([
            'success'      => true,
            'message'      => 'Evaluation updated and submitted for Head review successfully!',
            'review_link'  => $headReviewUrl,
            'review_token' => $link->token // <-- send token separately for frontend JS
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('Error updating evaluation', [
            'error_message' => $e->getMessage(),
            'trace'         => $e->getTraceAsString()
        ]);

        $message = env('APP_DEBUG')
            ? $e->getMessage()
            : 'An unexpected error occurred while updating the evaluation.';

        return response()->json([
            'success' => false,
            'message' => $message
        ], 500);
    }
}




    public function download($id)
    {
        $evaluation = Evaluation::findOrFail($id);

        // Use your existing PDF layout
        $pdf = Pdf::loadView('pdf.download', compact('evaluation'));

        return $pdf->download("evaluation-{$evaluation->id}.pdf");
    }


public function downloadSummary(Request $request)
{
    $ids = $request->input('ids', []);
    $ids = array_filter($ids, fn($id) => is_numeric($id));

    if (empty($ids)) {
        return redirect()->back()->with('error', 'No valid evaluations selected.');
    }

    $evaluations = Evaluation::whereIn('id', $ids)->get();

    if ($evaluations->isEmpty()) {
        return redirect()->back()->with('error', 'No evaluations found for selected IDs.');
    }

    // Load PDF with landscape orientation
    $pdf = Pdf::loadView('pdf.summary', compact('evaluations'))
              ->setPaper('a4', 'landscape'); // <-- landscape orientation

    return $pdf->download('evaluation-summary.pdf');
}

}
