<!DOCTYPE html>
<html>
<head>
    <title>Pagination Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center mb-4"> Collection Pagination</h2>

        <ul class="list-group mb-3">
            @foreach($items as $item)
                <li class="list-group-item">
                    Item {{ $item }}
                </li>
            @endforeach
        </ul>

        <div class="d-flex justify-content-center">
            {{ $items->links() }}
        </div>
    </div>
</div>

</body>
</html>
