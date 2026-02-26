<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Supplier Evaluation - {{ $evaluation->id ?? '' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        h1, h2, h3, h4 { margin: 0; }
        h1 { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        h2 { font-size: 14px; font-weight: bold; margin-bottom: 5px; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; margin-bottom: 5px; background-color: #f3f4f6; padding: 5px; border-left: 4px solid #3b82f6; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #999; padding: 6px; vertical-align: top; }
        .table th { background-color: #4b5563; color: white; text-align: left; font-weight: bold; }
        .remarks { min-height: 50px; }
        .rating-box { background-color: #d1fae5; padding: 10px; margin-bottom: 5px; text-align: center; font-weight: bold; }
        .digital-auth img { width: 100px; height: 100px; object-fit: cover; border: 1px solid #ccc; }
        .auth-panel { background-color: #fff; border: 1px solid #ccc; border-radius: 12px; padding: 16px; }
        .auth-container { display: flex; flex-wrap: wrap; gap: 16px; }
        .auth-panel img { margin-top: 8px; }
    </style>
</head>
<body>

<!-- Header -->
<div class="section" style="display: flex; align-items: center; justify-content: center; gap: 16px; text-align: center;">
    <!-- Logo -->
    <img src="{{ public_path('logo.png') }}"
         alt="Company Logo"
         style="height: 70px; width: auto; object-fit: contain;">

    <!-- Title -->
    <div>
        <h1 style="margin: 0; font-size: 18px; font-weight: bold;">SUPPLIER'S EVALUATION FORM</h1>
        <p style="margin: 0; font-size: 12px;">Performance Assessment & Rating System</p>
    </div>
</div>

    <!-- Instructions -->
    <div class="section">
        <div class="section-title">INSTRUCTIONS</div>
        <ol style="padding-left: 20px; margin-top: 5px;">
            <li>Check the box which corresponds to the supplier's performance based on the Purchase Order/Contract listed above.</li>
            <li>In the Remarks / Specific Comments Column, provide details of any incidents or deviations. Use additional sheet if necessary.</li>
            <li>When multiple POs are added, each evaluation is calculated separately for the overall rating.</li>
        </ol>
    </div>

    <!-- Evaluation Details -->
    <div class="section">
        <div class="section-title">Evaluation Details</div>
        <table class="table">
            <tr>
                <th>NAME OF SUPPLIER</th>
                <td>{{ $evaluation->supplier_name ?? '' }}</td>
                <th>Purchase Order / Contract No.</th>
                <td>{{ $evaluation->po_no ?? '' }}</td>
            </tr>
            <tr>
                <th>Date of Evaluation</th>
                <td>{{ $evaluation->date_evaluation ?? '' }}</td>
                <th>Covered Period</th>
                <td>{{ $evaluation->covered_period ?? '' }}</td>
            </tr>
            <tr>
                <th>Evaluated by (Office Name)</th>
                <td colspan="3">{{ $evaluation->office_name ?? '' }}</td>
            </tr>
        </table>
    </div>

    <!-- Evaluation Criteria Table -->
    <div class="section">
        <div class="section-title">Evaluation Criteria</div>
        <table class="table">
            <thead>
                <tr>
                    <th>EVALUATION CRITERIA</th>
                    <th>REMARKS / SPECIFIC COMMENTS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $criteriaWeightMap = [
                        1 => [4 => 20, 3 => 15, 2 => 10, 1 => 5],         // PRICE
                        2 => [4 => 30, 3 => 22.5, 2 => 15, 1 => 7.5],     // QUALITY
                        3 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25], // CUSTOMER CARE
                        4 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25], // DELIVERY
                    ];
                    $ratingDescriptions = [
                        1 => [
                            1 => "Bid amount is higher than the prevailing market price against the brand/services delivered.",
                            2 => "Goods delivered with recurring or significant damages/defects, affecting usability and functionality.",
                            3 => "If any three (3) of the customer care details are lacking.",
                            4 => "Goods/Services delivered eleven (11) or more days after expiration."
                        ],
                        2 => [
                            1 => "Some mismatch between bid amount and brand/services delivered; notably higher than market range.",
                            2 => "Goods delivered in accordance with specs but of low quality.",
                            3 => "If any two (2) of the customer care details are lacking.",
                            4 => "Goods/Services delivered six (6) to ten (10) days after expiration."
                        ],
                        3 => [
                            1 => "Bid amount generally aligns with brand/services delivered; minor deviations within acceptable range.",
                            2 => "Goods delivered with minor damages/defects, immediately corrected without affecting functionality.",
                            3 => "If one (1) of the customer care details is lacking.",
                            4 => "Goods/Services delivered one (1) to five (5) days after expiration."
                        ],
                        4 => [
                            1 => "Bid amount is reasonable based on the brand/services delivered; consistent with market rates; no overpricing.",
                            2 => "Goods delivered according to specifications and acceptable quality.",
                            3 => "Accessible, responsive to inquiries/complaints, adaptable to end-user needs, competent staff.",
                            4 => "Goods/Services delivered on time."
                        ],
                    ];
                    $overallScore = 0;
                @endphp

                @foreach($evaluation->criteriaScores as $score)
                    @php
                        $criteriaId = $score->criteria_id;
                        $rating = $score->number_rating ?? 0;
                        $weighted = $criteriaWeightMap[$criteriaId][$rating] ?? 0;
                        $overallScore += $weighted;
                        $criteriaName = $score->criteria->criteria_name ?? 'N/A';
                        $description = $ratingDescriptions[$rating][$criteriaId] ?? '';
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $criteriaName }}</strong>
                            ({{ max($criteriaWeightMap[$criteriaId]) }}%)
                            <br>
                            <strong>Rate:&nbsp;&nbsp;{{ number_format($weighted, 2) }}%</strong>
                            <br>
                            <em>{{ $description }}</em>
                        </td>
                        <td class="remarks">
                            {{ $score->remarks ?? '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Overall Rating -->
    <div class="section">
        <div class="section-title">Overall Rating</div>
        @php
            $status = $overallScore >= 60 ? 'Passed' : 'Failed';
        @endphp
        <div class="rating-box">
            <strong>{{ number_format($overallScore,2) }}%</strong> - {{ $status }}
        </div>
        <div style="display:flex; justify-content: space-around; margin-top:10px;">
            <div style="text-align:center;">
                <div>Passing Rate</div>
                <div>60%</div>
            </div>
        </div>
    </div>

<!-- Digital Authorization -->
<div class="section">
    <div class="section-title">Digital Authorization</div>

    <div style="display: flex; flex-wrap: wrap; gap: 16px; justify-content: space-between;">

        <!-- End User Panel (Left) -->
        <div style="flex: 1; min-width: 250px; background-color: #fff; border: 1px solid #ccc; border-radius: 12px; padding: 16px;">
            <h4 style="font-size: 16px; font-weight: bold; margin-bottom: 12px; border-bottom: 1px solid #ccc; padding-bottom: 8px;">
                End User
            </h4>
            <div>
                <div style="font-size: 12px; margin-bottom: 8px;">
                    <strong>Prepared by:</strong> {{ $evaluation->digitalApprovals->where('role','Prepared By')->first()->full_name ?? '-' }}<br>
                    <strong>Designation:</strong> {{ $evaluation->digitalApprovals->where('role','Prepared By')->first()->designation ?? '-' }}
                </div>
                <div style="font-size: 10px; color: #555; margin-bottom: 8px;">
                    Already submitted by End User
                </div>
                @if(!empty($evaluation->digitalApprovals->where('role','Prepared By')->first()->image))
                    <img src="{{ public_path('storage/' . $evaluation->digitalApprovals->where('role','Prepared By')->first()->image) }}"
                         alt="End User Signature"
                         style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ccc; border-radius: 8px;">
                @endif
            </div>
        </div>

        <!-- Head Authorization Panel (Right) -->
        <div style="flex: 1; min-width: 250px; background-color: #fff; border: 1px solid #ccc; border-radius: 12px; padding: 16px;">
            <h4 style="font-size: 16px; font-weight: bold; margin-bottom: 12px; border-bottom: 1px solid #ccc; padding-bottom: 8px;">
                Head Authorization
            </h4>
            <div>
                <div style="font-size: 12px; margin-bottom: 8px;">
                    <strong>Prepared by:</strong> {{ $evaluation->digitalApprovals->where('role','Head')->first()->full_name ?? '-' }}<br>
                    <strong>Designation:</strong> {{ $evaluation->digitalApprovals->where('role','Head')->first()->designation ?? '-' }}
                </div>
                <div style="font-size: 10px; color: #555; margin-bottom: 8px;">
                    Already submitted by Office Head
                </div>
                @if(!empty($evaluation->digitalApprovals->where('role','Head')->first()->image))
                    <img src="{{ public_path('storage/' . $evaluation->digitalApprovals->where('role','Head')->first()->image) }}"
                         alt="Head Signature"
                         style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ccc; border-radius: 8px;">
                @endif
            </div>
        </div>

    </div>

    <div style="text-align: center; margin-top: 16px; font-size: 11px; color: #555;">
        This is a system-generated document authenticated through computer-generated facial recognition technology and is valid without a handwritten signature.
    </div>
</div>

</body>
</html>
