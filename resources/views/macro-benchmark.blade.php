<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Performance Benchmarking & Memory Analytics</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f8fafc; color: #1e293b; font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar-dark { background: #0f172a !important; }
        .card { border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .pulse-badge { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #10b981; margin-right: 6px; box-shadow: 0 0 8px #10b981; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { opacity: 0.4; } 50% { opacity: 1; } 100% { opacity: 0.4; } }
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
                <a class="nav-link text-light active fw-bold" href="{{ route('macro.benchmark') }}">Benchmark</a>
                <a class="nav-link text-light" href="{{ route('macro.fuzzy') }}">Fuzzy Search</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        <div class="bg-white p-4 rounded-3 shadow-sm mb-4 border d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1">📊 Collection Performance Benchmarking & Memory Analytics</h2>
                <p class="text-muted mb-0">Benchmarking Execution Time (ms) and Memory Footprint (KB) across varying dataset sizes (1,000 to 25,000 items).</p>
            </div>
            <div>
                <button class="btn btn-outline-primary fw-bold" onclick="runBenchmark()">
                    <span class="pulse-badge"></span> Run Live Benchmark Stream
                </button>
            </div>
        </div>

        <!-- Metric Overview Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h6 class="text-muted mb-1">Dataset Sizes Tested</h6>
                    <h3 class="fw-bold text-primary mb-0">1K to 25K</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h6 class="text-muted mb-1">Avg Execution Speed</h6>
                    <h3 class="fw-bold text-success mb-0" id="avgSpeedVal">1.4 ms</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h6 class="text-muted mb-1">Memory Efficiency</h6>
                    <h3 class="fw-bold text-info mb-0" id="memEfficiencyVal">Optimal</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h6 class="text-muted mb-1">Last Tested Timestamp</h6>
                    <h3 class="fw-bold text-purple mb-0" id="lastTestTime" style="font-size: 18px; line-height: 1.8;">Just now</h3>
                </div>
            </div>
        </div>

        <!-- Visual Benchmark Charts -->
        <div class="row g-4 mb-4">

            <!-- Chart 1: Execution Time (ms) -->
            <div class="col-md-6">
                <div class="card p-4">
                    <h4 class="fw-bold mb-3 text-center">⚡ Execution Time Comparison (ms)</h4>
                    <canvas id="timeChart" style="max-height: 280px;"></canvas>
                </div>
            </div>

            <!-- Chart 2: Memory Footprint (KB) -->
            <div class="col-md-6">
                <div class="card p-4">
                    <h4 class="fw-bold mb-3 text-center">🧠 Peak Memory Footprint (KB)</h4>
                    <canvas id="memoryChart" style="max-height: 280px;"></canvas>
                </div>
            </div>

        </div>

        <!-- Benchmark Results Table -->
        <div class="card p-4">
            <h4 class="fw-bold mb-3">📋 Benchmark Results Breakdown Table</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Dataset Size</th>
                            <th>Filter + Map Time (ms)</th>
                            <th>Spatie Prioritize Time (ms)</th>
                            <th>Custom Fuzzy Search Time (ms)</th>
                            <th>Memory Allocated (KB)</th>
                        </tr>
                    </thead>
                    <tbody id="benchmarkTableBody">
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Click "Run Live Benchmark Stream" to measure performance metrics.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        let timeChart = null;
        let memoryChart = null;

        function initCharts(labels, filterMapTime, spatieTime, fuzzyTime, memoryKb) {
            const ctx1 = document.getElementById('timeChart').getContext('2d');
            if (timeChart) timeChart.destroy();

            timeChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Filter + Map (ms)', data: filterMapTime, backgroundColor: '#3b82f6', borderRadius: 4 },
                        { label: 'Spatie Prioritize (ms)', data: spatieTime, backgroundColor: '#10b981', borderRadius: 4 },
                        { label: 'Fuzzy Search (ms)', data: fuzzyTime, backgroundColor: '#f59e0b', borderRadius: 4 }
                    ]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true, title: { display: true, text: 'Milliseconds (ms)' } } }
                }
            });

            const ctx2 = document.getElementById('memoryChart').getContext('2d');
            if (memoryChart) memoryChart.destroy();

            memoryChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Memory Footprint (KB)', data: memoryKb, borderColor: '#8b5cf6', backgroundColor: 'rgba(139, 92, 246, 0.1)', fill: true, tension: 0.3 }
                    ]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true, title: { display: true, text: 'Kilobytes (KB)' } } }
                }
            });
        }

        function runBenchmark() {
            fetch('{{ route("macro.benchmark.json") }}')
                .then(r => r.json())
                .then(data => {
                    document.getElementById('lastTestTime').innerText = data.timestamp;

                    const labels = data.results.map(r => r.size);
                    const filterMapTime = data.results.map(r => r.filter_map_time_ms);
                    const spatieTime = data.results.map(r => r.spatie_prioritize_time_ms);
                    const fuzzyTime = data.results.map(r => r.fuzzy_search_time_ms);
                    const memoryKb = data.results.map(r => r.filter_map_mem_kb);

                    initCharts(labels, filterMapTime, spatieTime, fuzzyTime, memoryKb);

                    const tbody = document.getElementById('benchmarkTableBody');
                    tbody.innerHTML = '';
                    data.results.forEach(row => {
                        tbody.innerHTML += `
                            <tr>
                                <td><strong class="text-primary">${row.size}</strong></td>
                                <td><span class="badge bg-primary fs-6">${row.filter_map_time_ms} ms</span></td>
                                <td><span class="badge bg-success fs-6">${row.spatie_prioritize_time_ms} ms</span></td>
                                <td><span class="badge bg-warning text-dark fs-6">${row.fuzzy_search_time_ms} ms</span></td>
                                <td><code>${row.filter_map_mem_kb} KB</code></td>
                            </tr>
                        `;
                    });
                })
                .catch(err => console.error('Error running benchmark:', err));
        }

        document.addEventListener('DOMContentLoaded', runBenchmark);
    </script>
</body>
</html>
