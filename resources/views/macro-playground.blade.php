<!DOCTYPE html>
<html>
<head>
    <title>Macro Playground</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center mb-4"> Macro Playground</h2>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Macro</th>
                    <th>Output</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>
                            @if($value instanceof \Illuminate\Support\Collection)
                                <pre>{{ $value->toJson(JSON_PRETTY_PRINT) }}</pre>
                            @elseif(is_array($value))
                                <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                            @elseif(is_bool($value))
                                {{ $value ? 'true' : 'false' }}
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

</body>
</html>
