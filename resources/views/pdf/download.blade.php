<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Supplier Evaluation - {{ $evaluation->id ?? '' }}</title>
    <style>
        /* General Body */
        body { 
            font-family: "Segoe UI", Arial, sans-serif; 
            font-size: 12px; 
            color: #1f2937; 
            line-height: 1.5; 
            background-color: #f9fafb; 
            margin: 0; 
            padding: 20px; 
        }

        h1, h2, h3, h4 { margin: 0; }

        /* Header */
        .header { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 16px; 
            text-align: center; 
            margin-bottom: 24px;
        }

        .header h1 { 
            font-size: 18px; 
            font-weight: 700; 
            color: #1e40af; 
            margin-bottom: 4px; 
        }

        .header p { 
            font-size: 12px; 
            color: #475569; 
            margin: 0; 
        }

        /* Sections */
        .section { 
            margin-bottom: 24px; 
        }

        .section-title { 
            font-weight: 600; 
            margin-bottom: 8px; 
            background-color: #e0e7ff; 
            padding: 6px 10px; 
            border-left: 4px solid #1e40af; 
            color: #1e40af; 
        }

        /* Tables */
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 8px; 
        }

        .table th, .table td { 
            border: 1px solid #9ca3af; 
            padding: 8px; 
            vertical-align: top; 
        }

        .table th { 
            background-color: #1e40af; 
            color: white; 
            text-align: left; 
            font-weight: 600; 
        }

        td em { 
            color: #374151; 
            font-style: normal; 
            font-size: 11px; 
        }

        .remarks { 
            min-height: 50px; 
        }

        /* Overall Rating */
        .rating-box { 
            background-color: #dbeafe; 
            padding: 12px; 
            margin-bottom: 5px; 
            text-align: center; 
            font-weight: 600; 
            border-radius: 8px;
            border: 1px solid #3b82f6;
            color: #1e40af;
        }

        /* Digital Authorization Panels */
        .auth-container { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 24px; 
            justify-content: center; 
        }

        .auth-panel { 
            background-color: #ffffff; 
            border: 1px solid #cbd5e1; 
            border-radius: 12px; 
            padding: 20px; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.05); 
            display: inline-block;
            min-width: 260px;
        }

        .auth-panel h4 { 
            font-size: 16px; 
            font-weight: 600; 
            margin-bottom: 14px; 
            border-bottom: 1px solid #e5e7eb; 
            padding-bottom: 10px; 
            color: #1e3a8a; 
        }

        .auth-panel div { 
            display: flex; 
            flex-direction: column; 
            gap: 12px; 
            white-space: nowrap;
        }

        .auth-panel img { 
            margin-top: 8px; 
            width: 100px; 
            height: 100px; 
            object-fit: cover; 
            border: 1px solid #cbd5e1; 
            border-radius: 8px;
        }

        /* Footer note */
        .footer-note { 
            text-align: center; 
            margin-top: 20px; 
            font-size: 11px; 
            color: #6b7280; 
            font-style: italic; 
            line-height: 1.4; 
            max-width: 500px; 
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <!-- Logo Placeholder -->
    <!-- <img src="{{ public_path('logo.png') }}" alt="Company Logo" style="height:70px; object-fit:contain;"> -->

    <div>
        <h1>SUPPLIER'S EVALUATION FORM</h1>
        <p>Performance Assessment & Rating System</p>
    </div>
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
    <table class="table">
        <thead>
            <tr>
                <th>EVALUATION CRITERIA</th>
                <th>REMARKS / SPECIFIC COMMENTS</th>
            </tr>
        </thead>
        <tbody>
            @php $overallScore = 0; @endphp
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
                    <td class="remarks">{{ $score->remarks ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Overall Rating -->
<div class="section">
    @php $status = $overallScore >= 60 ? 'Passed' : 'Failed'; @endphp
    <div class="rating-box">
        <strong>{{ number_format($overallScore,2) }}%</strong> - {{ $status }}
    </div>
</div>

<!-- Digital Authorization -->
<div class="section auth-container">

    <!-- End User Panel -->
    <div class="auth-panel">
        <h4>End User</h4>
        <div>
            <div>
                <strong>Prepared by:</strong> {{ $evaluation->digitalApprovals->where('role','Prepared By')->first()->full_name ?? '-' }}<br>
                <strong>Designation:</strong> {{ $evaluation->digitalApprovals->where('role','Prepared By')->first()->designation ?? '-' }}
            </div>
            <div style="font-size:11px; color:#475569;">Already submitted by End User</div>
            @if(!empty($evaluation->digitalApprovals->where('role','Prepared By')->first()->image))
                <img hidden src="{{ public_path('storage/' . $evaluation->digitalApprovals->where('role','Prepared By')->first()->image) }}" alt="End User Signature">
            @endif
        </div>
    </div>

    <!-- Head Authorization Panel -->
    <div class="auth-panel">
        <h4>Head Authorization</h4>
        <div>
            <div>
                <strong>Prepared by:</strong> {{ $evaluation->digitalApprovals->where('role','Head')->first()->full_name ?? '-' }}<br>
                <strong>Designation:</strong> {{ $evaluation->digitalApprovals->where('role','Head')->first()->designation ?? '-' }}
            </div>
            <div style="font-size:11px; color:#475569;">Already submitted by Office Head</div>
            @if(!empty($evaluation->digitalApprovals->where('role','Head')->first()->image))
                <img hidden src="{{ public_path('storage/' . $evaluation->digitalApprovals->where('role','Head')->first()->image) }}" alt="Head Signature">
            @endif
        </div>
    </div>

</div>

<!-- Footer Note -->
<div class="footer-note">
    This is a system-generated document authenticated through computer-generated facial recognition technology and is valid without a handwritten signature.
</div>

</body>
</html>
