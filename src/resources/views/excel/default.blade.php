<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Default</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                @foreach($data_headers as $key => $dataHeader)
                @if(!in_array($dataHeader['column'],$exclude_columns))
                <th>{{$dataHeader['name']}}</th>
                @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data_queries as $key1 => $dq)
            <tr>
                @foreach($data_headers as $key2 => $dataHeader)
                @if($key2 == 0)
                <td>{{$key1+1}}</td>
                @else
                    @if(!in_array($dataHeader['column'],$exclude_columns))
                    <td>{{ $dq->{$dataHeader['column']} }}</td>
                    @endif
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>