<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intelligent Collection Fuzzy Search & Levenshtein Matching</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; color: #1e293b; font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar-dark { background: #0f172a !important; }
        .card { border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .score-pill { font-weight: bold; padding: 4px 12px; border-radius: 20px; font-size: 13px; }
        .score-high { background: #d1e7dd; color: #0f5132; }
        .score-medium { background: #fff3cd; color: #664d03; }
        .score-low { background: #f8d7da; color: #842029; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-info" href="{{ route('shop.filter') }}">⚡ Collection Macro Studio</a>
            <div class="navbar-nav gap-2">
                <a class="nav-link text-light" href="{{ url('/macro-test') }}">Basic Test</a>
                <a class="nav-link text-light" href="{{ url('/macro-playground') }}">Playground</a>
                <a class="nav-link text-light" href="{{ url('/shop-demo') }}">Shop Demo</a>
                <a class="nav-link text-light" href="{{ route('shop.filter') }}">Shop Filter</a>
                <a class="nav-link text-light" href="{{ route('product.analytics') }}">Analytics</a>
                <a class="nav-link text-light" href="{{ route('macro.builder') }}">Macro Builder</a>
                <a class="nav-link text-light" href="{{ route('macro.benchmark') }}">Benchmark</a>
                <a class="nav-link text-light active fw-bold" href="{{ route('macro.fuzzy') }}">Fuzzy Search</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        <div class="bg-white p-4 rounded-3 shadow-sm mb-4 border">
            <h2 class="fw-bold mb-1">🔍 Intelligent Collection Fuzzy Search & Levenshtein Matching Studio</h2>
            <p class="text-muted mb-0">Custom collection macros <code>Collection::fuzzySearch()</code> and <code>Collection::highlightMatches()</code> providing typo-tolerant search using String Distance & Similarity scoring.</p>
        </div>

        <!-- Search Input Card -->
        <div class="card p-4 mb-4">
            <form action="{{ route('macro.fuzzy') }}" method="GET">
                <label class="form-label fw-semibold">Try Typo-Tolerant Search Query:</label>
                <div class="input-group">
                    <input type="text" name="query" class="form-control form-control-lg" placeholder="Type query (e.g. shrt, jenes, soes, wtch, hood)..." value="{{ $query }}" required>
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">🔍 Search Collection</button>
                </div>
            </form>

            <div class="mt-3">
                <span class="text-muted small me-2">Quick Typo Tests:</span>
                @foreach($sampleQueries as $sq)
                    <a href="{{ route('macro.fuzzy', ['query' => $sq]) }}" class="btn btn-sm {{ $query === $sq ? 'btn-dark' : 'btn-outline-secondary' }} me-1 mb-1">
                        "{{ $sq }}"
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Search Results Table -->
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">🎯 Fuzzy Matched Results for "{{ $query }}"</h4>
                <span class="badge bg-primary fs-6">{{ count($searchResults) }} Products Found</span>
            </div>

            @if(count($searchResults))
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Product Name (Highlighted Match)</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Rating</th>
                                <th>Similarity Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($searchResults as $item)
                                <tr>
                                    <td>#{{ $item['id'] }}</td>
                                    <td>
                                        <span class="fs-6 font-monospace">{!! $item['name_highlighted'] ?? $item['name'] !!}</span>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $item['category'] }}</span></td>
                                    <td><strong class="text-success">₹{{ number_format($item['price']) }}</strong></td>
                                    <td>
                                        <span class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $i <= $item['rating'] ? '★' : '☆' }}
                                            @endfor
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $score = $item['similarity_score'] ?? 0;
                                            $class = $score >= 70 ? 'score-high' : ($score >= 40 ? 'score-medium' : 'score-low');
                                        @endphp
                                        <span class="score-pill {{ $class }}">
                                            ⚡ {{ $score }}% Match
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-5">
                    No products matched query "{{ $query }}" with current similarity threshold.
                </div>
            @endif
        </div>

    </div>

</body>
</html>
