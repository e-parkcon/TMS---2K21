<!DOCTYPE html>
<html lang="en">
<head>
    <title>Overtime Summary</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>empno</th>
                <th>cdate</th>
                <th>otdate</th>
                <th>appr_hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ot_summary as $ot_sum)
                <tr>
                    <td>{{ $ot_sum->empno }}</td>
                    <td>{{ date('m/d/y', strtotime($ot_sum->cdate)) }}</td>
                    <td>{{ date('m/d/y', strtotime($ot_sum->otdate)) }}</td>
                    <td>{{ $ot_sum->appr_hours }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>