<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
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
                <th>Nature of visit</th>
                <th>Person to visit</th>
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
            @foreach ($visitors as $visit)
                <tr>
                    <td>{{ $visit['txndate'] }} {{ $visit['txntime'] }}</td>
                    <td>{{ $visit['temperature'] }}</td>
                    <td>{{ $visit['name'] }}</td>
                    <td>{{ $visit['phone'] }}</td>
                    <td>{{ $visit['company'] }}</td>
                    <td>{{ $visit['nature_of_visit'] }}</td>
                    <td>{{ $visit['person_to_visit'] }}</td>
                    <td>{{ $visit['health_declaration']['q1_a'] }}</td>
                    <td>{{ $visit['health_declaration']['q1_b'] }}</td>
                    <td>{{ $visit['health_declaration']['q1_c'] }}</td>
                    <td>{{ $visit['health_declaration']['q1_d'] }}</td>
                    <td>{{ $visit['health_declaration']['q2'] }}</td>
                    <td>{{ $visit['health_declaration']['q3'] }}</td>
                    <td>{{ $visit['health_declaration']['q4'] }}</td>
                    <td>{{ $visit['health_declaration']['q5'] }}</td>
                    <td>{{ $visit['health_declaration']['other_place'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>