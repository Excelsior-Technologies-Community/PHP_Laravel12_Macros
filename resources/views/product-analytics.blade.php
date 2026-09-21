<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Product Analytics Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold">
            📊 Product Analytics & Statistics
        </h1>

        <p class="text-muted">
            Collection-based product analytics using Laravel macros
        </p>

    </div>


    {{-- Statistics Cards --}}

    <div class="row g-4 mb-5">

        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Total Products
                    </h6>

                    <h2 class="fw-bold">
                        {{ $totalProducts }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Total Value
                    </h6>

                    <h2 class="fw-bold">
                        ₹{{ number_format($totalValue) }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Average Price
                    </h6>

                    <h2 class="fw-bold">
                        ₹{{ number_format($averagePrice, 2) }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Minimum Price
                    </h6>

                    <h2 class="fw-bold">
                        ₹{{ number_format($minimumPrice) }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Maximum Price
                    </h6>

                    <h2 class="fw-bold">
                        ₹{{ number_format($maximumPrice) }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Average Rating
                    </h6>

                    <h2 class="fw-bold">
                        ⭐ {{ number_format($averageRating, 1) }}
                    </h2>

                </div>

            </div>

        </div>

    </div>


    {{-- Category Statistics --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">
                Category Statistics
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-light">

                        <tr>

                            <th>Category</th>

                            <th>Products</th>

                            <th>Total Value</th>

                            <th>Average Price</th>

                            <th>Average Rating</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($categoryStatistics as $category)

                        <tr>

                            <td>
                                <strong>
                                    {{ $category['category'] }}
                                </strong>
                            </td>

                            <td>
                                {{ $category['products'] }}
                            </td>

                            <td>
                                ₹{{ number_format($category['total_value']) }}
                            </td>

                            <td>
                                ₹{{ number_format($category['average_price'], 2) }}
                            </td>

                            <td>
                                ⭐ {{ number_format($category['average_rating'], 1) }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- Rating Distribution --}}

        <div class="col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">
                        Rating Distribution
                    </h5>

                </div>

                <div class="card-body">

                    @foreach($ratingStatistics as $rating => $statistics)

                        <div class="d-flex justify-content-between border-bottom py-2">

                            <span>
                                ⭐ {{ $rating }} Rating
                            </span>

                            <strong>
                                {{ $statistics['count'] }} Products
                            </strong>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- Unique Tags --}}

        <div class="col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-success text-white">

                    <h5 class="mb-0">
                        Available Product Tags
                    </h5>

                </div>

                <div class="card-body">

                    @foreach($uniqueTags as $tag)

                        <span class="badge text-bg-light border me-2 mb-2 p-2">
                            {{ $tag }}
                        </span>

                    @endforeach

                </div>

            </div>

        </div>

    </div>


    {{-- Expensive Products --}}

    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header bg-warning">

            <h5 class="mb-0">
                💰 Most Expensive Products
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Product</th>

                            <th>Category</th>

                            <th>Price</th>

                            <th>Rating</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($expensiveProducts as $product)

                        <tr>

                            <td>
                                {{ $product['name'] }}
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

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Top Rated Products --}}

    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header bg-info">

            <h5 class="mb-0">
                ⭐ Highest Rated Products
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                @foreach($topRatedProducts as $product)

                    <div class="col-md-4 mb-3">

                        <div class="border rounded p-3 h-100">

                            <h5>
                                {{ $product['name'] }}
                            </h5>

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

        </div>

    </div>


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