<!DOCTYPE html>
<html>
<head>
    <title>Shop Filter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h2 class="text-center mb-4"> Live Shop Filtering (Macros)</h2>

    <form method="GET" class="row g-3 mb-4">

        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <option value="Clothing">Clothing</option>
                <option value="Footwear">Footwear</option>
                <option value="Accessories">Accessories</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="tag" class="form-select">
                <option value="">All Tags</option>
                <option value="summer">Summer</option>
                <option value="winter">Winter</option>
                <option value="sports">Sports</option>
                <option value="luxury">Luxury</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="rating" class="form-select">
                <option value="">Min Rating</option>
                <option value="3">3+</option>
                <option value="4">4+</option>
                <option value="5">5</option>
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

    </form>

    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card p-3 shadow-sm">
                    <h5>{{ $product['name'] }}</h5>
                    <p>Category: {{ $product['category'] }}</p>
                    <p>Price:  {{ $product['price'] }}</p>
                    <p>Rating:  {{ $product['rating'] }}</p>
                    <p>Tags: {{ implode(', ', $product['tags']) }}</p>
                </div>
            </div>
        @empty
            <div class="alert alert-warning">
                No products found.
            </div>
        @endforelse
    </div>

</div>

</body>
</html>
