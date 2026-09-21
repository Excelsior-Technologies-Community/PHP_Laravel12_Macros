<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Product Sorting & Comparison</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold">
            🔄 Product Sorting & Comparison
        </h1>

        <p class="text-muted">
            Collection sorting, filtering and comparison
        </p>

    </div>


    {{-- Sorting --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET">

                @foreach(request()->input('products', []) as $id)

                    <input
                        type="hidden"
                        name="products[]"
                        value="{{ $id }}"
                    >

                @endforeach

                <div class="row g-3 align-items-end">

                    <div class="col-md-8">

                        <label class="form-label fw-bold">
                            Sort Products
                        </label>

                        <select
                            name="sort"
                            class="form-select"
                        >

                            <option
                                value="price_low"
                                {{ $sort === 'price_low' ? 'selected' : '' }}
                            >
                                Price: Low to High
                            </option>

                            <option
                                value="price_high"
                                {{ $sort === 'price_high' ? 'selected' : '' }}
                            >
                                Price: High to Low
                            </option>

                            <option
                                value="rating_high"
                                {{ $sort === 'rating_high' ? 'selected' : '' }}
                            >
                                Highest Rating
                            </option>

                            <option
                                value="name"
                                {{ $sort === 'name' ? 'selected' : '' }}
                            >
                                Product Name
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <button class="btn btn-primary w-100">
                            Apply Sorting
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Product Selection --}}

    <form method="GET">

        <input
            type="hidden"
            name="sort"
            value="{{ $sort }}"
        >

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">
                    Select Products for Comparison
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    @foreach($products as $product)

                        <div class="col-md-4 mb-3">

                            <div class="border rounded p-3 h-100">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="products[]"
                                        value="{{ $product['id'] }}"
                                        id="product{{ $product['id'] }}"
                                        {{ collect(request()->input('products', []))->contains($product['id']) ? 'checked' : '' }}
                                    >

                                    <label
                                        class="form-check-label"
                                        for="product{{ $product['id'] }}"
                                    >

                                        <strong>
                                            {{ $product['name'] }}
                                        </strong>

                                    </label>

                                </div>

                                <hr>

                                <p class="mb-1">
                                    Category:
                                    {{ $product['category'] }}
                                </p>

                                <p class="mb-1">
                                    Price:
                                    ₹{{ number_format($product['price']) }}
                                </p>

                                <p class="mb-0">
                                    Rating:
                                    ⭐ {{ $product['rating'] }}
                                </p>

                            </div>

                        </div>

                    @endforeach

                </div>

                <button class="btn btn-success">
                    Compare Selected Products
                </button>

            </div>

        </div>

    </form>


    {{-- Sorted Products --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                Sorted Products
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Rating</th>
                            <th>Tags</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($sortedProducts as $product)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <strong>
                                    {{ $product['name'] }}
                                </strong>
                            </td>

                            <td>
                                {{ $product['category'] }}
                            </td>

                            <td>
                                ₹{{ number_format($product['price']) }}
                            </td>

                            <td>
                                ⭐ {{ $product['rating'] }}
                            </td>

                            <td>
                                {{ implode(', ', $product['tags']) }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Comparison Results --}}

    @if($comparisonProducts->isNotEmpty())

        <div class="card shadow-sm border-0">

            <div class="card-header bg-success text-white">

                <h5 class="mb-0">
                    📊 Product Comparison
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3 mb-4">

                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small>
                                Products
                            </small>

                            <h3>
                                {{ $comparisonStats['product_count'] }}
                            </h3>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small>
                                Lowest Price
                            </small>

                            <h3>
                                ₹{{ number_format($comparisonStats['lowest_price']) }}
                            </h3>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small>
                                Highest Price
                            </small>

                            <h3>
                                ₹{{ number_format($comparisonStats['highest_price']) }}
                            </h3>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small>
                                Average Rating
                            </small>

                            <h3>
                                ⭐ {{ number_format($comparisonStats['average_rating'], 1) }}
                            </h3>

                        </div>

                    </div>

                </div>


                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead class="table-success">

                            <tr>

                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Rating</th>
                                <th>Tags</th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach($comparisonProducts as $product)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $product['name'] }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $product['category'] }}
                                </td>

                                <td>
                                    ₹{{ number_format($product['price']) }}
                                </td>

                                <td>
                                    ⭐ {{ $product['rating'] }}
                                </td>

                                <td>
                                    {{ implode(', ', $product['tags']) }}
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif


    <div class="text-center mt-4">

        <a
            href="{{ url('/') }}"
            class="btn btn-secondary"
        >
            Back to Home
        </a>

    </div>

</div>

</body>
</html>