<!DOCTYPE html>
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
</html>
