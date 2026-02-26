<?php

namespace App\Http\Controllers;

use App\Models\DigitalApproval;
use App\Models\Evaluation;
use App\Models\EvaluationLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\CriteriaScore;
use App\Models\EvaluationCriteria;

class HeadController extends Controller
{
    /**
     * Show the evaluation review page for Head using a token.
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function reviewPage($token)
    {
        try {
            // Find the active link and eager load evaluation relations
            $link = EvaluationLink::with([
                    'evaluation.criteriaScores',
                    'evaluation.digitalApprovals'
                ])
                ->where('token', $token)
                ->where('is_completed', false)
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                })
                ->firstOrFail();

            $evaluation = $link->evaluation;

            if (!$evaluation) {
                abort(404, 'Evaluation data not found for this link.');
            }

            return view('head.review', [
                'evaluation' => $evaluation,
                'token'      => $token
            ]);

        } catch (\Exception $e) {
            Log::warning('Failed to fetch evaluation by token', [
                'token' => $token,
                'error' => $e->getMessage()
            ]);

            abort(404, 'This review link is invalid or has expired.');
        }
    }

public function reviewEvaluation($token)
{
    $link = EvaluationLink::with([
            'evaluation.criteriaScores.criteria',
            'evaluation.digitalApprovals'
        ])
        ->active()
        ->where('token', $token)
        ->first();

    if (!$link) {
        abort(404, 'Invalid or expired evaluation link.');
    }

    $evaluation = $link->evaluation;

    $criteriaData = [];

    foreach ($evaluation->criteriaScores as $score) {

        if (!$score->criteria) {
            continue;
        }

        // Normalize database value
        $name = strtoupper(trim($score->criteria->criteria_name));

        switch ($name) {
            case 'PRICE':
                $key = 'price_1';
                break;

            case 'QUALITY/SERVICE LEVEL':
                $key = 'quality_1';
                break;

            case 'CUSTOMER CARE/AFTER SALES SERVICE':
                $key = 'customercare_1';
                break;

            case 'DELIVERY FULFILLMENT':
                $key = 'delivery_1';
                break;

            default:
                continue 2;
        }

        $criteriaData[$key] = [
            'value'   => $score->number_rating,
            'remarks' => $score->remarks
        ];
    }

    return response()->json([
        'evaluation_id'     => $evaluation->id,
        'supplier_name'     => $evaluation->supplier_name,
        'po_no'             => $evaluation->po_no,
        'date_evaluation'   => $evaluation->date_evaluation,
        'covered_period'    => $evaluation->covered_period,
        'office_name'       => $evaluation->office_name,
        'criteria'          => $criteriaData,
        'digital_approvals' => $evaluation->digitalApprovals
    ]);
}



public function updateEvaluation(Request $request, $token)
{
    DB::beginTransaction();

    try {
        // Find evaluation by token
        $link = EvaluationLink::with('evaluation.criteriaScores', 'evaluation.digitalApprovals')
                ->active()
                ->where('token', $token)
                ->firstOrFail();
        $evaluation = $link->evaluation;

        // ------------------------------
        // UPDATE MAIN EVALUATION INFO
        // ------------------------------
        $evaluation->update([
            'supplier_name'   => $request->supplier_name ?? $evaluation->supplier_name,
            'po_no'           => $request->po_no ?? $evaluation->po_no,
            'date_evaluation' => !empty($request->date_evaluation)
                                    ? Carbon::parse($request->date_evaluation)->timezone('Asia/Manila')
                                    : $evaluation->date_evaluation,
            'covered_period'  => $request->covered_period ?? $evaluation->covered_period,
            'office_name'     => $request->office_name ?? $evaluation->office_name,
        ]);

        // ------------------------------
        // UPDATE CRITERIA SCORES
        // ------------------------------
        $criteriaScores = $request->criteria ?? [];
        $criteriaIdMap = [
            'price_1'        => 1, // PRICE
            'quality_1'      => 2, // QUALITY/SERVICE LEVEL
            'customercare_1' => 3, // CUSTOMER CARE/AFTER SALES SERVICE
            'delivery_1'     => 4, // DELIVERY FULFILLMENT
        ];

        foreach ($criteriaScores as $key => $data) {
            if (!isset($criteriaIdMap[$key])) continue;

            $criteriaId = $criteriaIdMap[$key];

            \App\Models\CriteriaScore::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'criteria_id'   => $criteriaId
                ],
                [
                    'number_rating' => $data['value'] ?? null,
                    'remarks'       => $data['remarks'] ?? null
                ]
            );
        }

        // ------------------------------
        // HANDLE DIGITAL APPROVALS (Prepared By or Head)
        // ------------------------------
        if (!empty($request->evaluator) && !empty($request->role)) {
            $role = $request->role; // Should be either 'Prepared By' or 'Head'

            $digitalApproval = DigitalApproval::firstOrNew([
                'evaluation_id' => $evaluation->id,
                'role'          => $role
            ]);

            $digitalApproval->full_name   = $request->evaluator['full_name'] ?? $digitalApproval->full_name;
            $digitalApproval->designation = $request->evaluator['designation'] ?? $digitalApproval->designation;

            // Handle Base64 camera image
            if (!empty($request->evaluator['image']) && str_contains($request->evaluator['image'], 'data:image')) {
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->evaluator['image']));
                if ($imageData !== false) {
                    $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
                    $supabaseKey = env('SUPABASE_SERVICE_ROLE_KEY');
                    $bucket = 'image';

                    // Delete old image if exists
                    if (!empty($digitalApproval->image) && str_contains($digitalApproval->image, "$supabaseUrl/storage/v1/object/public/$bucket/")) {
                        $oldPath = str_replace("$supabaseUrl/storage/v1/object/public/$bucket/", '', $digitalApproval->image);
                        try {
                            Http::withHeaders([
                                'apikey' => $supabaseKey,
                                'Authorization' => 'Bearer ' . $supabaseKey,
                            ])->delete("$supabaseUrl/storage/v1/object/$bucket/$oldPath");
                        } catch (\Exception $ex) {
                            Log::warning("Could not delete old $role image: " . $ex->getMessage());
                        }
                    }

                    $fileName = 'evaluation_signatures/' . Str::uuid() . '.png';
                    try {
                        $response = Http::withHeaders([
                            'apikey'        => $supabaseKey,
                            'Authorization' => 'Bearer ' . $supabaseKey,
                            'Content-Type'  => 'image/png',
                        ])->withBody($imageData, 'image/png')
                          ->put("$supabaseUrl/storage/v1/object/$bucket/$fileName");

                        if ($response->successful()) {
                            $digitalApproval->image = "$supabaseUrl/storage/v1/object/public/$bucket/$fileName";
                        }
                    } catch (\Exception $ex) {
                        Log::warning("Could not upload $role image: " . $ex->getMessage());
                    }
                }
            }

            $digitalApproval->save();
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Evaluation submitted successfully!',
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error updating evaluation', [
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => env('APP_DEBUG') ? $e->getMessage() : 'An unexpected error occurred.'
        ], 500);
    }
}
}
