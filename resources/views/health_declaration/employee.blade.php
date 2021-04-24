<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Temperature</th>
                <th>Name</th>
                <th>Mobile #</th>
                <th>Company</th>
                <th>Body /  Muscle Pains</th>
                <th>Headache</th>
                <th>Fever</th>
                <th>Difficulty in breathing</th>
                <th>Have you worked together or stayed in the same close environment of confirmed COVID-19 case? </th>
                <th>Have you had any contact with anyone with fever, cough, colds, and sore throat in the past 2 weeks?</th>
                <th>Have you travelled outside of the Philippines in the last 14 days?</th>
                <th>Have you travelled to any area in NCR aside from your home?</th>
                <th>If your answer in the last question is YES please state where.</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $emp)
                <tr>
                    <td>{{ $emp['txndate'] }} {{ $emp['txntime'] }}</td>
                    <td>{{ $emp['temperature'] }}</td>
                    <td>{{ $emp['name'] }}</td>
                    <td>{{ $emp['phone'] }}</td>
                    <td>{{ $emp['company'] }}</td>
                    <td>{{ $emp['health_declaration']['q1_a'] }}</td>
                    <td>{{ $emp['health_declaration']['q1_b'] }}</td>
                    <td>{{ $emp['health_declaration']['q1_c'] }}</td>
                    <td>{{ $emp['health_declaration']['q1_d'] }}</td>
                    <td>{{ $emp['health_declaration']['q2'] }}</td>
                    <td>{{ $emp['health_declaration']['q3'] }}</td>
                    <td>{{ $emp['health_declaration']['q4'] }}</td>
                    <td>{{ $emp['health_declaration']['q5'] }}</td>
                    <td>{{ $emp['health_declaration']['other_place'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>