<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribers Report</title>
</head>

<body>
    <h1 style="text-align:center;">Subscribers Report</h1>
    <table border="1" style="font-size: 14px; font-family: Helvetica; width: 100%; border-collapse:collapse;">
        <thead>
            <tr style="background-color: aliceblue;">
                <th>Email</th>
                <th>Active</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscribers as $v)
            <tr>
                <td style="padding-left: 10px;">{{ $v->email}} </td>
                <td style="text-align: center;">{{ $v->is_active ? 'Active' : 'Non-Active'}} </td>
                <td style="text-align: center;">{{ $v->created_at}} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>