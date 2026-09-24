<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Collection Macro Builder & Registry Studio</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; color: #1e293b; font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar-dark { background: #0f172a !important; }
        .card { border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .code-preview { background: #0f172a; color: #38bdf8; padding: 14px; border-radius: 8px; font-family: monospace; font-size: 13px; overflow-x: auto; }
        .badge-macro { background: #0284c7; color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px; }
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
                <a class="nav-link text-light active fw-bold" href="{{ route('macro.builder') }}">Macro Builder</a>
                <a class="nav-link text-light" href="{{ route('macro.benchmark') }}">Benchmark</a>
                <a class="nav-link text-light" href="{{ route('macro.fuzzy') }}">Fuzzy Search</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        <div class="bg-white p-4 rounded-3 shadow-sm mb-4 border">
            <h2 class="fw-bold mb-1">⚡ Custom Collection Macro Builder & Registry Studio</h2>
            <p class="text-muted mb-0">Live creation, dynamic registration, and interactive execution testing of custom Laravel Collection Macros.</p>
        </div>

        @if(session('sandbox_result'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h5 class="fw-bold mb-1">✓ Sandbox Execution Result for Macro: <code>{{ session('sandbox_result.macro') }}()</code></h5>
                <p class="mb-2 text-dark"><strong>Input Array:</strong> <code>[{{ session('sandbox_result.input') }}]</code></p>
                <div class="code-preview">{{ session('sandbox_result.output') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Interactive Sandbox Form -->
        <div class="card p-4 mb-4">
            <h4 class="fw-bold text-primary mb-3">🧪 Interactive Macro Execution Sandbox</h4>
            <form action="{{ route('macro.builder.test') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Select Custom Macro:</label>
                    <select name="macro" class="form-select">
                        <option value="stats">Collection::stats() (min, max, avg, sum, count, median)</option>
                        <option value="toPercentiles">Collection::toPercentiles() (25th, 50th, 75th, 90th)</option>
                        <option value="slugify">Collection::slugify() (URL Slugs converter)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Sample Input Values (comma-separated):</label>
                    <input type="text" name="input" class="form-control" value="150, 300, 450, 600, 900, 1200, 1500, 2500" placeholder="e.g. 10, 20, 30, 40">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Execute Macro</button>
                </div>
            </form>
        </div>

        <!-- 4 Live Registered Macro Demos -->
        <div class="row g-4">

            <!-- Macro 1: stats() -->
            <div class="col-md-6">
                <div class="card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">1. 📊 <code>stats()</code> Macro</h4>
                        <span class="badge-macro">Collection Macro</span>
                    </div>
                    <p class="text-muted small">Calculates count, min, max, sum, average, and median in a single collection call.</p>
                    <div class="code-preview mb-3">$prices->stats();</div>
                    <h6 class="fw-semibold">Live Result:</h6>
                    <pre class="bg-light p-3 rounded border text-dark mb-0">{{ json_encode($statsResult, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>

            <!-- Macro 2: toPercentiles() -->
            <div class="col-md-6">
                <div class="card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">2. 📈 <code>toPercentiles()</code> Macro</h4>
                        <span class="badge-macro">Collection Macro</span>
                    </div>
                    <p class="text-muted small">Computes the 25th, 50th, 75th, and 90th percentiles for numerical items.</p>
                    <div class="code-preview mb-3">$prices->toPercentiles();</div>
                    <h6 class="fw-semibold">Live Result:</h6>
                    <pre class="bg-light p-3 rounded border text-dark mb-0">{{ json_encode($percentilesResult, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>

            <!-- Macro 3: slugify() -->
            <div class="col-md-6">
                <div class="card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">3. 🔗 <code>slugify()</code> Macro</h4>
                        <span class="badge-macro">Collection Macro</span>
                    </div>
                    <p class="text-muted small">Transforms string elements or specific array keys into clean URL slugs.</p>
                    <div class="code-preview mb-3">$titles->slugify();</div>
                    <h6 class="fw-semibold">Live Result:</h6>
                    <pre class="bg-light p-3 rounded border text-dark mb-0">{{ json_encode($slugsResult->all(), JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>

            <!-- Macro 4: groupByMulti() -->
            <div class="col-md-6">
                <div class="card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">4. 🗂️ <code>groupByMulti()</code> Macro</h4>
                        <span class="badge-macro">Collection Macro</span>
                    </div>
                    <p class="text-muted small">Performs dynamic multi-level nested grouping across array keys.</p>
                    <div class="code-preview mb-3">$products->groupByMulti(['category', 'rating']);</div>
                    <h6 class="fw-semibold">Live Result Structure:</h6>
                    <pre class="bg-light p-3 rounded border text-dark mb-0" style="max-height: 180px; overflow-y: auto;">{{ json_encode($groupedResult->toArray(), JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
