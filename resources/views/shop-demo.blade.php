<!DOCTYPE html>
<html>
<head>
    <title>Shop Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4"> Macro Powered Shop Demo</h2>

    <div class="card p-3 mb-4">
        <h4>Summer Products (containsAny)</h4>
        <pre>{{ $summerProducts->toJson(JSON_PRETTY_PRINT) }}</pre>
    </div>

    <div class="card p-3 mb-4">
        <h4>Denim + Winter (containsAll)</h4>
        <pre>{{ $denimWinter->toJson(JSON_PRETTY_PRINT) }}</pre>
    </div>

    <div class="card p-3 mb-4">
        <h4>Prioritized (Rating 5 First)</h4>
        <pre>{{ $prioritized->toJson(JSON_PRETTY_PRINT) }}</pre>
    </div>

    <div class="card p-3 mb-4">
        <h4>Section By Category</h4>
        <pre>{{ $sections->toJson(JSON_PRETTY_PRINT) }}</pre>
    </div>

    <div class="card p-3 mb-4">
        <h4>Name & Price (pluckMany)</h4>
        <pre>{{ $nameAndPrice->toJson(JSON_PRETTY_PRINT) }}</pre>
    </div>

    <div class="card p-3 mb-4">
        <h4> Random Featured (weightedRandom)</h4>
        <pre>{{ json_encode($randomFeatured, JSON_PRETTY_PRINT) }}</pre>
    </div>

</div>

</body>
</html>
