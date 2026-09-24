<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Tests\TestCase;

class MacroAdvancedFeaturesTest extends TestCase
{
    /**
     * Test custom collection macro stats()
     */
    public function test_stats_macro_calculates_correct_metrics(): void
    {
        $numbers = collect([10, 20, 30, 40, 50]);
        $stats = $numbers->stats();

        $this->assertEquals(5, $stats['count']);
        $this->assertEquals(150, $stats['sum']);
        $this->assertEquals(30, $stats['avg']);
        $this->assertEquals(10, $stats['min']);
        $this->assertEquals(50, $stats['max']);
        $this->assertEquals(30, $stats['median']);
        $this->assertArrayHasKey('std_dev', $stats);
    }

    /**
     * Test custom collection macro toPercentiles()
     */
    public function test_percentiles_macro_returns_percentile_ranks(): void
    {
        $numbers = collect(range(1, 100));
        $percentiles = $numbers->toPercentiles();

        $this->assertArrayHasKey('p25', $percentiles);
        $this->assertArrayHasKey('p50', $percentiles);
        $this->assertArrayHasKey('p75', $percentiles);
        $this->assertArrayHasKey('p90', $percentiles);
        $this->assertArrayHasKey('p99', $percentiles);
        $this->assertEquals(50.5, $percentiles['p50']);
    }

    /**
     * Test custom collection macro slugify()
     */
    public function test_slugify_macro_converts_strings_to_slugs(): void
    {
        $titles = collect(['Laravel 12 Macros!', 'Hello World & PHP']);
        $slugs = $titles->slugify();

        $this->assertEquals(['laravel-12-macros', 'hello-world-php'], $slugs->toArray());
    }

    /**
     * Test custom collection macro groupByMulti()
     */
    public function test_group_by_multi_macro_groups_by_multiple_keys(): void
    {
        $data = collect([
            ['dept' => 'IT', 'role' => 'Dev', 'name' => 'Alex'],
            ['dept' => 'IT', 'role' => 'Dev', 'name' => 'Sam'],
            ['dept' => 'HR', 'role' => 'Recruiter', 'name' => 'Jane']
        ]);

        $grouped = $data->groupByMulti(['dept', 'role']);

        $this->assertTrue(isset($grouped['IT']['Dev']));
        $this->assertCount(2, $grouped['IT']['Dev']);
        $this->assertTrue(isset($grouped['HR']['Recruiter']));
    }

    /**
     * Test custom collection macro fuzzySearch()
     */
    public function test_fuzzy_search_macro_handles_typos(): void
    {
        $products = collect([
            ['name' => 'Shirt', 'category' => 'Clothing'],
            ['name' => 'Jeans', 'category' => 'Clothing'],
            ['name' => 'Shoes', 'category' => 'Footwear'],
            ['name' => 'Watch', 'category' => 'Accessories'],
        ]);

        // Search typo "shrt" should match "Shirt"
        $results = $products->fuzzySearch('shrt', ['name', 'category']);

        $this->assertGreaterThan(0, $results->count());
        $this->assertEquals('Shirt', $results->first()['name']);
    }

    /**
     * Test macro builder view route
     */
    public function test_macro_builder_page_loads_successfully(): void
    {
        $response = $this->get('/macro-builder');
        $response->assertStatus(200);
        $response->assertSee('Custom Collection Macro Builder');
    }

    /**
     * Test custom macro test execution sandbox
     */
    public function test_macro_builder_sandbox_executes_dynamic_macro(): void
    {
        $response = $this->post('/macro-builder/test', [
            'input' => '10, 20, 30, 40, 50',
            'macro' => 'stats'
        ]);

        $response->assertRedirect('/macro-builder');
        $response->assertSessionHas('sandbox_result');
    }

    /**
     * Test performance benchmark view page
     */
    public function test_benchmark_page_loads_successfully(): void
    {
        $response = $this->get('/macro-benchmark');
        $response->assertStatus(200);
        $response->assertSee('Collection Performance Benchmarking');
    }

    /**
     * Test performance benchmark JSON API endpoint
     */
    public function test_benchmark_json_returns_performance_analytics(): void
    {
        $response = $this->getJson('/macro-benchmark-json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'timestamp',
            'results' => [
                '*' => [
                    'size',
                    'filter_map_time_ms',
                    'filter_map_mem_kb',
                    'spatie_prioritize_time_ms',
                    'spatie_prioritize_mem_kb',
                    'fuzzy_search_time_ms',
                    'fuzzy_search_mem_kb',
                ]
            ]
        ]);
    }

    /**
     * Test fuzzy search studio view page
     */
    public function test_fuzzy_search_page_loads_successfully(): void
    {
        $response = $this->get('/macro-fuzzy?query=jenes');
        $response->assertStatus(200);
        $response->assertSee('Intelligent Collection Fuzzy Search');
        $response->assertSee('Jeans');
    }
}
