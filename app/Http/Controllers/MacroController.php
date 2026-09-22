<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MacroController extends Controller
{
    /**
     * Shared product collection.
     */
    private function products()
    {
        return collect([
            [
                'id' => 1,
                'name' => 'Shirt',
                'category' => 'Clothing',
                'price' => 1200,
                'tags' => ['cotton', 'summer'],
                'rating' => 4,
            ],
            [
                'id' => 2,
                'name' => 'Jeans',
                'category' => 'Clothing',
                'price' => 2500,
                'tags' => ['denim', 'winter'],
                'rating' => 5,
            ],
            [
                'id' => 3,
                'name' => 'Shoes',
                'category' => 'Footwear',
                'price' => 3000,
                'tags' => ['sports', 'running'],
                'rating' => 3,
            ],
            [
                'id' => 4,
                'name' => 'Watch',
                'category' => 'Accessories',
                'price' => 5000,
                'tags' => ['luxury'],
                'rating' => 5,
            ],
            [
                'id' => 5,
                'name' => 'Jacket',
                'category' => 'Clothing',
                'price' => 4000,
                'tags' => ['winter'],
                'rating' => 4,
            ],
            [
                'id' => 6,
                'name' => 'T-Shirt',
                'category' => 'Clothing',
                'price' => 900,
                'tags' => ['cotton', 'summer', 'casual'],
                'rating' => 4,
            ],
            [
                'id' => 7,
                'name' => 'Running Shoes',
                'category' => 'Footwear',
                'price' => 3500,
                'tags' => ['sports', 'running', 'fitness'],
                'rating' => 5,
            ],
            [
                'id' => 8,
                'name' => 'Sunglasses',
                'category' => 'Accessories',
                'price' => 1800,
                'tags' => ['summer', 'fashion'],
                'rating' => 3,
            ],
            [
                'id' => 9,
                'name' => 'Hoodie',
                'category' => 'Clothing',
                'price' => 2800,
                'tags' => ['winter', 'casual'],
                'rating' => 5,
            ],
            [
                'id' => 10,
                'name' => 'Smart Watch',
                'category' => 'Accessories',
                'price' => 6500,
                'tags' => ['luxury', 'fitness', 'technology'],
                'rating' => 5,
            ],
        ]);
    }


    /**
     * Product Analytics Dashboard.
     */
    public function productAnalytics()
    {
        $products = $this->products();

        $totalProducts = $products->count();

        $totalValue = $products->sum('price');

        $averagePrice = $products->avg('price');

        $minimumPrice = $products->min('price');

        $maximumPrice = $products->max('price');

        $averageRating = $products->avg('rating');

        $categoryStatistics = $products
            ->groupBy('category')
            ->map(function ($items, $category) {
                return [
                    'category' => $category,
                    'products' => $items->count(),
                    'total_value' => $items->sum('price'),
                    'average_price' => $items->avg('price'),
                    'average_rating' => $items->avg('rating'),
                ];
            })
            ->values();

        $ratingStatistics = $products
            ->groupBy('rating')
            ->map(function ($items, $rating) {
                return [
                    'rating' => $rating,
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('rating')
            ->values();

        $expensiveProducts = $products
            ->sortByDesc('price')
            ->take(3)
            ->values();

        $topRatedProducts = $products
            ->prioritize(fn ($product) => $product['rating'] === 5)
            ->values();

        $uniqueTags = $products
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('product-analytics', compact(
            'totalProducts',
            'totalValue',
            'averagePrice',
            'minimumPrice',
            'maximumPrice',
            'averageRating',
            'categoryStatistics',
            'ratingStatistics',
            'expensiveProducts',
            'topRatedProducts',
            'uniqueTags'
        ));
    }


    /**
     * Product Sorting & Comparison Dashboard.
     */
    public function productComparison(Request $request)
    {
        $products = $this->products();

        $sort = $request->get('sort', 'price_low');

        switch ($sort) {
            case 'price_high':
                $sortedProducts = $products->sortByDesc('price')->values();
                break;

            case 'rating_high':
                $sortedProducts = $products->sortByDesc('rating')->values();
                break;

            case 'name':
                $sortedProducts = $products->sortBy('name')->values();
                break;

            case 'price_low':
            default:
                $sortedProducts = $products->sortBy('price')->values();
                break;
        }

        $selectedIds = collect($request->input('products', []))
            ->map(fn ($id) => (int) $id);

        $comparisonProducts = $products
            ->filter(function ($product) use ($selectedIds) {
                return $selectedIds->contains($product['id']);
            })
            ->values();

        $comparisonStats = null;

        if ($comparisonProducts->isNotEmpty()) {
            $comparisonStats = [
                'lowest_price' => $comparisonProducts->min('price'),
                'highest_price' => $comparisonProducts->max('price'),
                'average_price' => $comparisonProducts->avg('price'),
                'highest_rating' => $comparisonProducts->max('rating'),
                'average_rating' => $comparisonProducts->avg('rating'),
                'product_count' => $comparisonProducts->count(),
            ];
        }

        return view('product-comparison', compact(
            'products',
            'sortedProducts',
            'comparisonProducts',
            'comparisonStats',
            'sort'
        ));
    }


    /**
     * Smart Product Recommendations.
     */
    public function productRecommendations(Request $request)
    {
        $products = $this->products();

        $selectedCategory = $request->get('category');

        $selectedTag = $request->get('tag');

        $minimumRating = (int) $request->get('rating', 0);

        $recommendations = $products;

        if ($selectedCategory) {
            $recommendations = $recommendations->filter(function ($product) use ($selectedCategory) {
                return $product['category'] === $selectedCategory;
            });
        }

        if ($selectedTag) {
            $recommendations = $recommendations->filter(function ($product) use ($selectedTag) {
                return collect($product['tags'])
                    ->containsAny([$selectedTag]);
            });
        }

        if ($minimumRating > 0) {
            $recommendations = $recommendations->filter(function ($product) use ($minimumRating) {
                return $product['rating'] >= $minimumRating;
            });
        }

        $recommendations = $recommendations
            ->prioritize(fn ($product) => $product['rating'] === 5)
            ->values();

        $featuredProduct = null;

        if ($recommendations->isNotEmpty()) {
            $featuredProduct = $recommendations->weightedRandom('rating');
        }

        $scoredRecommendations = $recommendations
            ->map(function ($product) {
                $score =
                    ($product['rating'] * 20)
                    + (count($product['tags']) * 5);

                $product['recommendation_score'] = $score;

                return $product;
            })
            ->sortByDesc('recommendation_score')
            ->values();

        return view('product-recommendations', [
            'products' => $products,
            'recommendations' => $scoredRecommendations,
            'featuredProduct' => $featuredProduct,
            'selectedCategory' => $selectedCategory,
            'selectedTag' => $selectedTag,
            'minimumRating' => $minimumRating,
        ]);
    }


    /**
     * Advanced Shop Filter.
     *
     * New functionality:
     *
     * 1. Search
     * 2. Category
     * 3. Multiple tags
     * 4. Minimum price
     * 5. Maximum price
     * 6. Sorting
     * 7. Pagination
     */
    public function shopFilter(Request $request)
    {
        $products = $this->products();

        /*
        |--------------------------------------------------------------------------
        | 1. Search
        |--------------------------------------------------------------------------
        */

        $search = trim($request->get('search', ''));

        if ($search !== '') {
            $products = $products->filter(function ($product) use ($search) {
                return str_contains(
                    strtolower($product['name']),
                    strtolower($search)
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Category Filter
        |--------------------------------------------------------------------------
        */

        $category = $request->get('category');

        if ($category) {
            $products = $products->where('category', $category);
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Multiple Tag Filtering
        |--------------------------------------------------------------------------
        */

        $tags = $request->input('tags', []);

        if (!is_array($tags)) {
            $tags = [$tags];
        }

        $tags = collect($tags)
            ->filter()
            ->values();

        if ($tags->isNotEmpty()) {
            $products = $products->filter(function ($product) use ($tags) {
                return collect($product['tags'])
                    ->containsAny($tags->all());
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Minimum Price
        |--------------------------------------------------------------------------
        */

        $minPrice = $request->get('min_price');

        if ($minPrice !== null && $minPrice !== '') {
            $products = $products->filter(function ($product) use ($minPrice) {
                return $product['price'] >= (float) $minPrice;
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 5. Maximum Price
        |--------------------------------------------------------------------------
        */

        $maxPrice = $request->get('max_price');

        if ($maxPrice !== null && $maxPrice !== '') {
            $products = $products->filter(function ($product) use ($maxPrice) {
                return $product['price'] <= (float) $maxPrice;
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 6. Sorting
        |--------------------------------------------------------------------------
        */

        $sort = $request->get('sort', 'default');

        switch ($sort) {
            case 'price_low':
                $products = $products->sortBy('price');
                break;

            case 'price_high':
                $products = $products->sortByDesc('price');
                break;

            case 'rating_high':
                $products = $products->sortByDesc('rating');
                break;

            case 'name':
                $products = $products->sortBy('name');
                break;

            default:
                $products = $products
                    ->prioritize(fn ($product) => $product['rating'] === 5);
                break;
        }

        $products = $products->values();


        /*
        |--------------------------------------------------------------------------
        | 7. Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = (int) $request->get('per_page', 5);

        if (!in_array($perPage, [5, 10, 15])) {
            $perPage = 5;
        }

        $page = (int) $request->get('page', 1);

        $paginatedProducts = $products->paginate($perPage);

        /*
        |--------------------------------------------------------------------------
        | Wishlist
        |--------------------------------------------------------------------------
        */

        $wishlist = session()->get('wishlist', []);


        /*
        |--------------------------------------------------------------------------
        | Filter Data
        |--------------------------------------------------------------------------
        */

        $categories = $this->products()
            ->pluck('category')
            ->unique()
            ->sort()
            ->values();

        $allTags = $this->products()
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('shop-filter', [
            'products' => $paginatedProducts,
            'search' => $search,
            'category' => $category,
            'tags' => $tags,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'sort' => $sort,
            'perPage' => $perPage,
            'wishlist' => $wishlist,
            'categories' => $categories,
            'allTags' => $allTags,
        ]);
    }


    /**
     * Add product to wishlist.
     *
     * Functionality #8
     */
    public function addToWishlist(Request $request, $id)
    {
        $product = $this->products()
            ->firstWhere('id', (int) $id);

        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        $wishlist = session()->get('wishlist', []);

        if (!in_array($product['id'], $wishlist)) {
            $wishlist[] = $product['id'];
            session()->put('wishlist', $wishlist);

            return back()->with(
                'success',
                $product['name'] . ' added to wishlist.'
            );
        }

        return back()->with(
            'info',
            $product['name'] . ' is already in your wishlist.'
        );
    }


    /**
     * Remove product from wishlist.
     */
    public function removeFromWishlist(Request $request, $id)
    {
        $wishlist = session()->get('wishlist', []);

        $wishlist = collect($wishlist)
            ->reject(fn ($wishlistId) => (int) $wishlistId === (int) $id)
            ->values()
            ->all();

        session()->put('wishlist', $wishlist);

        return back()->with(
            'success',
            'Product removed from wishlist.'
        );
    }


    /**
     * CSV Export.
     *
     * Functionality #9
     */
    public function exportCsv(Request $request)
    {
        $products = $this->getFilteredProducts($request);

        $filename = 'products-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'Name',
                'Category',
                'Price',
                'Rating',
                'Tags',
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product['id'],
                    $product['name'],
                    $product['category'],
                    $product['price'],
                    $product['rating'],
                    implode(', ', $product['tags']),
                ]);
            }

            fclose($file);
        };

        return Response::stream(
            $callback,
            200,
            $headers
        );
    }


    /**
     * JSON Export.
     *
     * Functionality #10
     */
    public function exportJson(Request $request)
    {
        $products = $this->getFilteredProducts($request);

        return response()->json([
            'success' => true,
            'count' => $products->count(),
            'exported_at' => now()->toDateTimeString(),
            'products' => $products->values(),
        ]);
    }


    /**
     * Shared filtering logic for exports.
     */
    private function getFilteredProducts(Request $request)
    {
        $products = $this->products();

        $search = trim($request->get('search', ''));

        if ($search !== '') {
            $products = $products->filter(function ($product) use ($search) {
                return str_contains(
                    strtolower($product['name']),
                    strtolower($search)
                );
            });
        }

        $category = $request->get('category');

        if ($category) {
            $products = $products->where('category', $category);
        }

        $tags = $request->input('tags', []);

        if (!is_array($tags)) {
            $tags = [$tags];
        }

        $tags = collect($tags)
            ->filter()
            ->values();

        if ($tags->isNotEmpty()) {
            $products = $products->filter(function ($product) use ($tags) {
                return collect($product['tags'])
                    ->containsAny($tags->all());
            });
        }

        $minPrice = $request->get('min_price');

        if ($minPrice !== null && $minPrice !== '') {
            $products = $products->filter(function ($product) use ($minPrice) {
                return $product['price'] >= (float) $minPrice;
            });
        }

        $maxPrice = $request->get('max_price');

        if ($maxPrice !== null && $maxPrice !== '') {
            $products = $products->filter(function ($product) use ($maxPrice) {
                return $product['price'] <= (float) $maxPrice;
            });
        }

        $sort = $request->get('sort', 'default');

        switch ($sort) {
            case 'price_low':
                $products = $products->sortBy('price');
                break;

            case 'price_high':
                $products = $products->sortByDesc('price');
                break;

            case 'rating_high':
                $products = $products->sortByDesc('rating');
                break;

            case 'name':
                $products = $products->sortBy('name');
                break;

            default:
                $products = $products->prioritize(
                    fn ($product) => $product['rating'] === 5
                );
                break;
        }

        return $products->values();
    }
}
