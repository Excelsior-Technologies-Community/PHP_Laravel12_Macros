<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Advanced Macro Shop</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .hero {
            background: linear-gradient(135deg,
                    #212529,
                    #495057);

            color: white;
            border-radius: 18px;
            padding: 35px;
        }

        .product-card {
            transition: 0.25s;
            border: 0;
            border-radius: 15px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
        }

        .tag {
            font-size: 12px;
        }

        .stat-card {
            border: 0;
            border-radius: 15px;
        }

        .wishlist-active {
            color: #dc3545;
        }
    </style>

</head>

<body>

    <div class="container py-5">


        {{-- Header --}}

        <div class="hero mb-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h1 class="fw-bold">
                        🛍️ Advanced Macro Shop
                    </h1>

                    <p class="mb-0">
                        Search, filter, sort, paginate and export
                        Laravel Collection data.
                    </p>

                </div>

                <div class="text-end">

                    <div class="small">
                        Wishlist
                    </div>

                    <h3 class="mb-0">
                        ❤️ {{ count($wishlist) }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Success/Error Messages --}}

        @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            ✅ {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

        @endif


        @if(session('info'))

        <div class="alert alert-info alert-dismissible fade show">

            ℹ️ {{ session('info') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

        @endif


        @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            ❌ {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

        @endif


        {{-- Filter Form --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">
                    🔎 Advanced Product Filters
                </h5>

            </div>

            <div class="card-body">

                <form
                    method="GET"
                    action="{{ route('shop.filter') }}">

                    {{-- Search --}}

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label fw-bold">
                                Product Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search by product name..."
                                value="{{ $search }}">

                        </div>


                        {{-- Category --}}

                        <div class="col-md-6">

                            <label class="form-label fw-bold">
                                Category
                            </label>

                            <select
                                name="category"
                                class="form-select">

                                <option value="">
                                    All Categories
                                </option>

                                @foreach($categories as $item)

                                <option
                                    value="{{ $item }}"
                                    {{ $category === $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Multiple Tags --}}

                        <div class="col-md-12">

                            <label class="form-label fw-bold">
                                Tags
                            </label>

                            <div class="row">

                                @foreach($allTags as $tag)

                                <div class="col-md-3 mb-2">

                                    <div class="form-check">

                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="tags[]"
                                            value="{{ $tag }}"
                                            id="tag_{{ $loop->index }}"
                                            {{ $tags->contains($tag) ? 'checked' : '' }}>

                                        <label
                                            class="form-check-label"
                                            for="tag_{{ $loop->index }}">
                                            {{ ucfirst($tag) }}
                                        </label>

                                    </div>

                                </div>

                                @endforeach

                            </div>

                        </div>


                        {{-- Minimum Price --}}

                        <div class="col-md-3">

                            <label class="form-label fw-bold">
                                Minimum Price
                            </label>

                            <input
                                type="number"
                                name="min_price"
                                class="form-control"
                                placeholder="₹ Minimum"
                                value="{{ $minPrice }}"
                                min="0">

                        </div>


                        {{-- Maximum Price --}}

                        <div class="col-md-3">

                            <label class="form-label fw-bold">
                                Maximum Price
                            </label>

                            <input
                                type="number"
                                name="max_price"
                                class="form-control"
                                placeholder="₹ Maximum"
                                value="{{ $maxPrice }}"
                                min="0">

                        </div>


                        {{-- Sorting --}}

                        <div class="col-md-3">

                            <label class="form-label fw-bold">
                                Sort By
                            </label>

                            <select
                                name="sort"
                                class="form-select">

                                <option
                                    value="default"
                                    {{ $sort === 'default' ? 'selected' : '' }}>
                                    Recommended
                                </option>

                                <option
                                    value="price_low"
                                    {{ $sort === 'price_low' ? 'selected' : '' }}>
                                    Price: Low → High
                                </option>

                                <option
                                    value="price_high"
                                    {{ $sort === 'price_high' ? 'selected' : '' }}>
                                    Price: High → Low
                                </option>

                                <option
                                    value="rating_high"
                                    {{ $sort === 'rating_high' ? 'selected' : '' }}>
                                    Rating: High → Low
                                </option>

                                <option
                                    value="name"
                                    {{ $sort === 'name' ? 'selected' : '' }}>
                                    Name: A → Z
                                </option>

                            </select>

                        </div>


                        {{-- Per Page --}}

                        <div class="col-md-3">

                            <label class="form-label fw-bold">
                                Products Per Page
                            </label>

                            <select
                                name="per_page"
                                class="form-select">

                                <option
                                    value="5"
                                    {{ $perPage == 5 ? 'selected' : '' }}>
                                    5
                                </option>

                                <option
                                    value="10"
                                    {{ $perPage == 10 ? 'selected' : '' }}>
                                    10
                                </option>

                                <option
                                    value="15"
                                    {{ $perPage == 15 ? 'selected' : '' }}>
                                    15
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Buttons --}}

                    <div class="mt-4 d-flex gap-2 flex-wrap">

                        <button
                            type="submit"
                            class="btn btn-primary">
                            🔎 Apply Filters
                        </button>


                        <a
                            href="{{ route('shop.filter') }}"
                            class="btn btn-outline-secondary">
                            🔄 Reset
                        </a>

                    </div>

                </form>

            </div>

        </div>


        {{-- Export Buttons --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>

                        <strong>
                            Export Current Results
                        </strong>

                        <div class="text-muted small">
                            Export using the currently selected filters.
                        </div>

                    </div>


                    <div class="d-flex gap-2">

                        {{-- CSV --}}

                        <a
                            href="{{ route('shop.export.csv', request()->query()) }}"
                            class="btn btn-success">
                            📥 Export CSV
                        </a>


                        {{-- JSON --}}

                        <a
                            href="{{ route('shop.export.json', request()->query()) }}"
                            class="btn btn-dark"
                            target="_blank">
                            📋 Export JSON
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- Result Count --}}

        <div class="row g-3 mb-4">

            <div class="col-md-4">

                <div class="card stat-card shadow-sm">

                    <div class="card-body text-center">

                        <small class="text-muted">
                            Matching Products
                        </small>

                        <h2 class="fw-bold">
                            {{ $products->total() }}
                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card stat-card shadow-sm">

                    <div class="card-body text-center">

                        <small class="text-muted">
                            Current Page
                        </small>

                        <h2 class="fw-bold">
                            {{ $products->currentPage() }}
                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card stat-card shadow-sm">

                    <div class="card-body text-center">

                        <small class="text-muted">
                            Wishlist Items
                        </small>

                        <h2 class="fw-bold">
                            ❤️ {{ count($wishlist) }}
                        </h2>

                    </div>

                </div>

            </div>

        </div>


        {{-- Products --}}

        <div class="row">

            @forelse($products as $product)

            <div class="col-md-4 mb-4">

                <div class="card product-card shadow-sm h-100">

                    <div class="card-body">


                        <div class="d-flex justify-content-between">

                            <h4 class="fw-bold">
                                {{ $product['name'] }}
                            </h4>


                            @if(in_array($product['id'], $wishlist))

                            <span class="wishlist-active">
                                ❤️
                            </span>

                            @else

                            <span>
                                🤍
                            </span>

                            @endif

                        </div>


                        <span class="badge bg-secondary mb-3">

                            {{ $product['category'] }}

                        </span>


                        <h3 class="text-primary">

                            ₹{{ number_format($product['price']) }}

                        </h3>


                        <p class="mb-2">

                            <strong>
                                Rating:
                            </strong>

                            ⭐ {{ $product['rating'] }}

                        </p>


                        <div class="mb-3">

                            @foreach($product['tags'] as $tag)

                            <span class="badge bg-light text-dark border tag me-1">

                                {{ $tag }}

                            </span>

                            @endforeach

                        </div>


                        {{-- Wishlist Button --}}

                        @if(in_array($product['id'], $wishlist))

                        <form
                            method="POST"
                            action="{{ route('wishlist.remove', $product['id']) }}">

                            @csrf

                            @method('DELETE')

                            <button
                                class="btn btn-outline-danger w-100">
                                💔 Remove Wishlist
                            </button>

                        </form>

                        @else

                        <form
                            method="POST"
                            action="{{ route('wishlist.add', $product['id']) }}">

                            @csrf

                            <button
                                class="btn btn-outline-danger w-100">
                                ❤️ Add Wishlist
                            </button>

                        </form>

                        @endif

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12">

                <div class="alert alert-warning text-center">

                    <h5>
                        No products found.
                    </h5>

                    <p class="mb-0">
                        Try changing your filters.
                    </p>

                </div>

            </div>

            @endforelse

        </div>


        {{-- Pagination --}}

        @if($products->hasPages())

        <div class="card border-0 shadow-sm mt-3">

            <div class="card-body">

                <div class="d-flex justify-content-center">

                    {{ $products->appends(request()->query())->links() }}

                </div>

            </div>

        </div>

        @endif


        {{-- Macro Information --}}

        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-info">

                <h5 class="mb-0">
                    Collection Macro Features
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <ul>

                            <li>
                                <strong>filter()</strong>
                                — Product filtering
                            </li>

                            <li>
                                <strong>where()</strong>
                                — Category filtering
                            </li>

                            <li>
                                <strong>containsAny()</strong>
                                — Multi-tag matching
                            </li>

                            <li>
                                <strong>sortBy()</strong>
                                — Ascending sorting
                            </li>

                            <li>
                                <strong>sortByDesc()</strong>
                                — Descending sorting
                            </li>

                        </ul>

                    </div>

                    <div class="col-md-6">

                        <ul>

                            <li>
                                <strong>prioritize()</strong>
                                — Recommended products first
                            </li>

                            <li>
                                <strong>paginate()</strong>
                                — Collection pagination
                            </li>

                            <li>
                                <strong>pluck()</strong>
                                — Category/tag extraction
                            </li>

                            <li>
                                <strong>flatten()</strong>
                                — Tag collection flattening
                            </li>

                            <li>
                                <strong>unique()</strong>
                                — Remove duplicate tags
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>


        {{-- Navigation --}}

        <div class="text-center mt-4">

            <a
                href="{{ url('/') }}"
                class="btn btn-secondary">
                ← Back to Home
            </a>

            <a
                href="{{ route('product.analytics') }}"
                class="btn btn-primary">
                📊 Analytics
            </a>

            <a
                href="{{ route('product.comparison') }}"
                class="btn btn-success">
                🔄 Comparison
            </a>

            <a
                href="{{ route('product.recommendations') }}"
                class="btn btn-warning">
                🎯 Recommendations
            </a>

        </div>


    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>