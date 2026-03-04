<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Supplier Evaluation Summary</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
            margin: 40px;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
            border: none;
        }

        .header td {
            border: none;
            vertical-align: middle;
        }

        .logo {
            width: 90px;
        }

        .gov-title {
            text-align: center;
        }

        .gov-title h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .gov-title h3 {
            margin: 0;
            font-size: 14px;
            font-weight: normal;
        }

        h1 {
            text-align: center;
            font-size: 16px;
            margin: 20px 0;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #e5e7eb;
            font-weight: bold;
        }

        .average {
            margin-top: 15px;
            font-weight: bold;
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 8px;
            line-height: 1.5;
        }

        .auth-text {
            font-style: italic;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Government Header -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 20%;">
                    <img src="{{ public_path('logo.png') }}" class="logo">
                </td>
                <td class="gov-title">
                    <!-- <h2>Government of [Country Name]</h2>
                    <h3>Ministry of Procurement & Supply Chain</h3>
                    <h3>Department of Vendor Management</h3> -->
                </td>
                <!-- <td style="width: 20%; text-align: right; font-size: 11px;">
                    Ref No: ___________<br>
                    Date: {{ date('d M Y') }}
                </td> -->
            </tr>
        </table>
    </div>

    <h1>Supplier Evaluation Summary Report</h1>

    <table>
        <thead>
            <tr>
                <th>PO Number</th>
                <th>Supplier Name</th>
                <th>Evaluation Date</th>
                <th>Evaluation Score (%)</th>
            </tr>
        </thead>
        <tbody>

            @php
                $criteriaWeightMap = [
                    1 => [4 => 20, 3 => 15, 2 => 10, 1 => 5],
                    2 => [4 => 30, 3 => 22.5, 2 => 15, 1 => 7.5],
                    3 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
                    4 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
                ];

                $totalScore = 0;
                $evaluationsCount = count($evaluations);
            @endphp

            @foreach($evaluations as $evaluation)
                @php
                    $overallScore = 0;
                    foreach($evaluation->criteriaScores as $score) {
                        $criteriaId = $score->criteria_id;
                        $rating = $score->number_rating ?? 0;
                        $weighted = $criteriaWeightMap[$criteriaId][$rating] ?? 0;
                        $overallScore += $weighted;
                    }
                    $totalScore += $overallScore;
                @endphp
                <tr>
                    <td>{{ $evaluation->po_no ?? '' }}</td>
                    <td>{{ $evaluation->supplier_name ?? '' }}</td>
                    <td>{{ $evaluation->date_evaluation ?? '' }}</td>
                    <td>{{ number_format($overallScore, 2) }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    @php
        $averageScore = $evaluationsCount > 0 ? number_format($totalScore / $evaluationsCount, 2) : 'N/A';
    @endphp

    <div class="average">
        Overall Average Score: {{ $averageScore }}%
    </div>

    <!-- Footer -->
    <div class="footer">
        This document is confidential and intended for official use only.<br>
        <div class="auth-text">
            This Supplier Evaluation is authenticated and authorized through computer-generated facial recognition technology, which serves as an official signature in lieu of a handwritten signature.
        </div>
        <br>
        © {{ date('Y') }} Government of [Country Name]. All Rights Reserved.
    </div>

</body>
</html>

<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Evaluation Summary</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        h1 { text-align: center; margin-bottom: 20px; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #4b5563; color: white; }
        .average { margin-top: 12px; font-weight: bold; text-align: right; }
        .landscape-container { width: 100%; }
    </style>
</head>
<body>
    <h1>Supplier Evaluation Summary</h1>

    <div class="landscape-container">
        <table>
            <thead>
                <tr>
                    <th>PO Number</th>
                    <th>Supplier Name</th>
                    <th>Evaluation Date</th>
                    <th>Evaluation Score (%)</th>
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
                    $totalScore = 0;
                    $evaluationsCount = count($evaluations);
                @endphp

                @foreach($evaluations as $evaluation)
                    @php
                        $overallScore = 0;
                        foreach($evaluation->criteriaScores as $score) {
                            $criteriaId = $score->criteria_id;
                            $rating = $score->number_rating ?? 0;
                            $weighted = $criteriaWeightMap[$criteriaId][$rating] ?? 0;
                            $overallScore += $weighted;
                        }
                        $totalScore += $overallScore;
                    @endphp
                    <tr>
                        <td>{{ $evaluation->po_no ?? '' }}</td>
                        <td>{{ $evaluation->supplier_name ?? '' }}</td>
                        <td>{{ $evaluation->date_evaluation ?? '' }}</td>
                        <td>{{ number_format($overallScore, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $averageScore = $evaluationsCount > 0 ? number_format($totalScore / $evaluationsCount, 2) : 'N/A';
        @endphp

        <div class="average">
            Average Evaluation Score: {{ $averageScore }}%
        </div>
    </div>
</body>
</html> -->
