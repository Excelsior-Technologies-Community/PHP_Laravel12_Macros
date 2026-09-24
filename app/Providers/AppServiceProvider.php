<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        /*
        |--------------------------------------------------------------------------
        | Custom Collection Macros
        |--------------------------------------------------------------------------
        */

        // 1. Stats Macro: min, max, avg, sum, count, median, std_dev
        if (!Collection::hasMacro('stats')) {
            Collection::macro('stats', function ($key = null) {
                $values = $key ? $this->pluck($key)->filter()->values() : $this->filter()->values();

                if ($values->isEmpty()) {
                    return [
                        'count' => 0,
                        'min' => 0,
                        'max' => 0,
                        'sum' => 0,
                        'avg' => 0,
                        'median' => 0,
                        'std_dev' => 0,
                    ];
                }

                $sorted = $values->sort()->values();
                $count = $sorted->count();
                $middle = (int) floor($count / 2);
                $median = ($count % 2 === 0)
                    ? ($sorted[$middle - 1] + $sorted[$middle]) / 2
                    : $sorted[$middle];

                $avg = $sorted->avg();
                $variance = $sorted->reduce(fn ($carry, $item) => $carry + pow($item - $avg, 2), 0) / $count;
                $stdDev = sqrt($variance);

                return [
                    'count' => $count,
                    'min' => $sorted->first(),
                    'max' => $sorted->last(),
                    'sum' => round($sorted->sum(), 2),
                    'avg' => round($avg, 2),
                    'median' => round($median, 2),
                    'std_dev' => round($stdDev, 2),
                ];
            });
        }

        // 2. Percentiles Macro: 25th, 50th, 75th, 90th, 99th percentiles
        if (!Collection::hasMacro('toPercentiles')) {
            Collection::macro('toPercentiles', function ($key = null) {
                $values = ($key ? $this->pluck($key) : $this)->filter()->sort()->values();
                $count = $values->count();

                if ($count === 0) {
                    return ['p25' => 0, 'p50' => 0, 'p75' => 0, 'p90' => 0, 'p99' => 0];
                }

                $getPercentile = function ($p) use ($values, $count) {
                    $index = ($p / 100) * ($count - 1);
                    $lower = floor($index);
                    $fraction = $index - $lower;

                    if (isset($values[$lower + 1])) {
                        return round($values[$lower] + $fraction * ($values[$lower + 1] - $values[$lower]), 2);
                    }

                    return round($values[$lower], 2);
                };

                return [
                    'p25' => $getPercentile(25),
                    'p50' => $getPercentile(50),
                    'p75' => $getPercentile(75),
                    'p90' => $getPercentile(90),
                    'p99' => $getPercentile(99),
                ];
            });
        }

        // 3. Slugify Macro: Converts text items/keys into URL Slugs
        if (!Collection::hasMacro('slugify')) {
            Collection::macro('slugify', function ($key = null) {
                return $this->map(function ($item) use ($key) {
                    $value = $key ? (is_array($item) ? ($item[$key] ?? '') : ($item->{$key} ?? '')) : (string) $item;
                    return Str::slug($value);
                });
            });
        }

        // 4. Multi-Level Grouping Macro
        if (!Collection::hasMacro('groupByMulti')) {
            Collection::macro('groupByMulti', function (array $keys) {
                if (empty($keys)) {
                    return $this;
                }

                $firstKey = array_shift($keys);
                $grouped = $this->groupBy($firstKey);

                if (empty($keys)) {
                    return $grouped;
                }

                return $grouped->map(fn($group) => $group->groupByMulti($keys));
            });
        }

        // 5. Intelligent Fuzzy Search Macro (Levenshtein Distance + Substring matching)
        if (!Collection::hasMacro('fuzzySearch')) {
            Collection::macro('fuzzySearch', function ($search, $key = 'name', $threshold = 30) {
                $searchStr = strtolower(trim((string) $search));

                if ($searchStr === '') {
                    return $this;
                }

                $keys = is_array($key) ? $key : [$key];

                return $this->map(function ($item) use ($searchStr, $keys) {
                    $maxSim = 0;

                    foreach ($keys as $k) {
                        $target = strtolower(trim((string) (is_array($item) ? ($item[$k] ?? '') : ($item->{$k} ?? ''))));

                        if ($target === '') {
                            $sim = 0;
                        } elseif (str_contains($target, $searchStr)) {
                            $sim = 90; // High score for substring match
                        } else {
                            $lev = levenshtein($searchStr, $target);
                            $maxLen = max(strlen($searchStr), strlen($target));
                            $sim = $maxLen > 0 ? round((1 - ($lev / $maxLen)) * 100, 1) : 0;
                        }

                        if ($sim > $maxSim) {
                            $maxSim = $sim;
                        }
                    }

                    $itemCopy = is_array($item) ? $item : clone $item;
                    if (is_array($itemCopy)) {
                        $itemCopy['similarity_score'] = $maxSim;
                    } else {
                        $itemCopy->similarity_score = $maxSim;
                    }

                    return $itemCopy;
                })
                ->filter(function ($item) use ($threshold) {
                    $score = is_array($item) ? ($item['similarity_score'] ?? 0) : ($item->similarity_score ?? 0);
                    return $score >= $threshold;
                })
                ->sortByDesc(function ($item) {
                    return is_array($item) ? ($item['similarity_score'] ?? 0) : ($item->similarity_score ?? 0);
                })
                ->values();
            });
        }

        // 6. Highlight Matches Macro
        if (!Collection::hasMacro('highlightMatches')) {
            Collection::macro('highlightMatches', function ($search, $key = 'name') {
                $searchStr = trim((string) $search);

                if ($searchStr === '') {
                    return $this;
                }

                return $this->map(function ($item) use ($searchStr, $key) {
                    $itemCopy = is_array($item) ? $item : clone $item;
                    $text = is_array($item) ? ($item[$key] ?? '') : ($item->{$key} ?? '');

                    if ($text && preg_match('/' . preg_quote($searchStr, '/') . '/i', $text)) {
                        $highlighted = preg_replace('/(' . preg_quote($searchStr, '/') . ')/i', '<mark class="bg-warning text-dark px-1 rounded">$1</mark>', $text);
                        if (is_array($itemCopy)) {
                            $itemCopy[$key . '_highlighted'] = $highlighted;
                        } else {
                            $itemCopy->{$key . '_highlighted'} = $highlighted;
                        }
                    } else {
                        if (is_array($itemCopy)) {
                            $itemCopy[$key . '_highlighted'] = htmlspecialchars($text);
                        } else {
                            $itemCopy->{$key . '_highlighted'} = htmlspecialchars($text);
                        }
                    }

                    return $itemCopy;
                });
            });
        }
    }
}

