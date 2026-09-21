<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Smart Product Recommendations</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold">
            🎯 Smart Product Recommendations
        </h1>

        <p class="text-muted">
            Collection macros for intelligent product recommendations
        </p>

    </div>


    {{-- Recommendation Filters --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">
                Recommendation Preferences
            </h5>

        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Category
                        </label>

                        <select
                            name="category"
                            class="form-select"
                        >

                            <option value="">
                                All Categories
                            </option>

                            <option
                                value="Clothing"
                                {{ $selectedCategory === 'Clothing' ? 'selected' : '' }}
                            >
                                Clothing
                            </option>

                            <option
                                value="Footwear"
                                {{ $selectedCategory === 'Footwear' ? 'selected' : '' }}
                            >
                                Footwear
                            </option>

                            <option
                                value="Accessories"
                                {{ $selectedCategory === 'Accessories' ? 'selected' : '' }}
                            >
                                Accessories
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Preferred Tag
                        </label>

                        <select
                            name="tag"
                            class="form-select"
                        >

                            <option value="">
                                All Tags
                            </option>

                            <option
                                value="summer"
                                {{ $selectedTag === 'summer' ? 'selected' : '' }}
                            >
                                Summer
                            </option>

                            <option
                                value="winter"
                                {{ $selectedTag === 'winter' ? 'selected' : '' }}
                            >
                                Winter
                            </option>

                            <option
                                value="sports"
                                {{ $selectedTag === 'sports' ? 'selected' : '' }}
                            >
                                Sports
                            </option>

                            <option
                                value="running"
                                {{ $selectedTag === 'running' ? 'selected' : '' }}
                            >
                                Running
                            </option>

                            <option
                                value="luxury"
                                {{ $selectedTag === 'luxury' ? 'selected' : '' }}
                                >
                                Luxury
                            </option>

                            <option
                                value="denim"
                                {{ $selectedTag === 'denim' ? 'selected' : '' }}
                            >
                                Denim
                            </option>

                            <option
                                value="cotton"
                                {{ $selectedTag === 'cotton' ? 'selected' : '' }}
                            >
                                Cotton
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Minimum Rating
                        </label>

                        <select
                            name="rating"
                            class="form-select"
                        >

                            <option value="0">
                                Any Rating
                            </option>

                            <option
                                value="3"
                                {{ $minimumRating == 3 ? 'selected' : '' }}
                            >
                                3+
                            </option>

                            <option
                                value="4"
                                {{ $minimumRating == 4 ? 'selected' : '' }}
                            >
                                4+
                            </option>

                            <option
                                value="5"
                                {{ $minimumRating == 5 ? 'selected' : '' }}
                            >
                                5
                            </option>

                        </select>

                    </div>

                </div>


                <div class="mt-4">

                    <button class="btn btn-primary">
                        Generate Recommendations
                    </button>

                    <a
                        href="{{ url('/product-recommendations') }}"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- Featured Product --}}

    @if($featuredProduct)

        <div class="card shadow border-0 mb-5">

            <div class="card-body text-center p-5">

                <div class="mb-2">
                    ⭐ FEATURED RECOMMENDATION
                </div>

                <h2 class="fw-bold">
                    {{ $featuredProduct['name'] }}
                </h2>

                <p class="text-muted">
                    {{ $featuredProduct['category'] }}
                </p>

                <h3 class="text-primary">
                    ₹{{ number_format($featuredProduct['price']) }}
                </h3>

                <p>
                    Rating:
                    ⭐ {{ $featuredProduct['rating'] }}
                </p>

                <div>

                    @foreach($featuredProduct['tags'] as $tag)

                        <span class="badge text-bg-light border">
                            {{ $tag }}
                        </span>

                    @endforeach

                </div>

            </div>

        </div>

    @endif


    {{-- Recommendations --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-success text-white">

            <h5 class="mb-0">
                Recommended Products
            </h5>

        </div>

        <div class="card-body">

            @if($recommendations->isEmpty())

                <div class="alert alert-warning mb-0">

                    No products match your selected preferences.

                </div>

            @else

                <div class="row">

                    @foreach($recommendations as $product)

                        <div class="col-md-4 mb-4">

                            <div class="card h-100 shadow-sm">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between">

                                        <h5 class="card-title">

                                            {{ $product['name'] }}

                                        </h5>

                                        <span class="badge text-bg-primary">

                                            Score:
                                            {{ $product['recommendation_score'] }}

                                        </span>

                                    </div>

                                    <hr>

                                    <p class="mb-2">

                                        <strong>
                                            Category:
                                        </strong>

                                        {{ $product['category'] }}

                                    </p>

                                    <p class="mb-2">

                                        <strong>
                                            Price:
                                        </strong>

                                        ₹{{ number_format($product['price']) }}

                                    </p>

                                    <p class="mb-2">

                                        <strong>
                                            Rating:
                                        </strong>

                                        ⭐ {{ $product['rating'] }}

                                    </p>

                                    <p class="mb-2">

                                        <strong>
                                            Tags:
                                        </strong>

                                    </p>

                                    @foreach($product['tags'] as $tag)

                                        <span class="badge text-bg-light border me-1">

                                            {{ $tag }}

                                        </span>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

    </div>


    {{-- Macro Explanation --}}

    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header bg-info">

            <h5 class="mb-0">
                Collection Macro Techniques Used
            </h5>

        </div>

        <div class="card-body">

            <ul class="mb-0">

                <li>
                    <strong>containsAny()</strong>
                    — Matches products using selected tags.
                </li>

                <li>
                    <strong>prioritize()</strong>
                    — Places highly-rated products first.
                </li>

                <li>
                    <strong>weightedRandom()</strong>
                    — Selects a featured product using rating weights.
                </li>

                <li>
                    <strong>filter()</strong>
                    — Applies recommendation preferences.
                </li>

                <li>
                    <strong>map()</strong>
                    — Calculates recommendation scores.
                </li>

                <li>
                    <strong>sortByDesc()</strong>
                    — Orders products by recommendation score.
                </li>

            </ul>

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