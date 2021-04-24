<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leave Summary</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>empno</th>
                <th>leavecode</th>
                <th>cdate</th>
                <th>fdate</th>
                <th>tdate</th>
                <th>app_days</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lv_summary as $lv_sum)
                <tr>
                    <td>{{ $lv_sum->empno }}</td>
                    <td>{{ $lv_sum->leavecode }}</td>
                    <td>{{ date('m/d/y', strtotime($lv_sum->cdate)) }}</td>
                    <td>{{ date('m/d/y', strtotime($lv_sum->fdate)) }}</td>
                    <td>{{ date('m/d/y', strtotime($lv_sum->tdate)) }}</td>
                    <td>{{ $lv_sum->app_days }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>