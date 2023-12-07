<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
</head>
<style>
.table-pdf {
    width: 100%;
    border-collapse: collapse;
}

.table-pdf thead {
    background: #f5f5f5;
}

.table-pdf th {
    padding: 4px;
}

td {
    padding: 4px;
}
</style>

<body>
    <table border="1px" class='table-pdf'>
        <thead>
            <tr>
                @if($enable_number)
                <th>No</th>
                @endif
                @foreach($data_headers as $key => $dh)
                @if(!in_array($dh['column'],$exclude_columns))
                <th>{{$dh['name']}}</th>
                @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data_queries as $key => $dq)
            <tr>
                @if($enable_number)
                <td>{{$key+1}}</td>
                @endif
                @foreach($data_headers as $key => $dh)
                @if(!in_array($dh['column'],$exclude_columns))
                <td>{{ $dq->{$dh['column']} }}</td>
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>