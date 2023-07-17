<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditrails Report</title>
</head>
<body>
    <h1 style="text-align:center;">Auditrails Report</h1>
    <table border="1" style="font-size: 14px; width: 100%; border-collapse:collapse;">
        <thead>
            <tr style="background-color: aliceblue;">
                <th>User ID</th>
                <th>Activity</th>
                <th>Name</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auditrails as $v)
            <tr>
                <td style="text-align: center;">{{ $v->user_id}} </td>
                <td style="padding-left: 10px;">{{ $v->activity}} </td>
                <td style="text-align: center;">{{ $v->name_user}} </td>
                <td style="text-align: center;">{{ $v->created_at}} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>