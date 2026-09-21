<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MacroController extends Controller
{
    /**
     * Shared product collection used by the new Macro features.
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
        ]);
    }


    /**
     * Product Analytics & Statistics Dashboard.
     */
    public function productAnalytics()
    {
        $products = $this->products();

        /*
        |--------------------------------------------------------------------------
        | Basic Product Statistics
        |--------------------------------------------------------------------------
        */

        $totalProducts = $products->count();

        $totalValue = $products->sum('price');

        $averagePrice = $products->avg('price');

        $minimumPrice = $products->min('price');

        $maximumPrice = $products->max('price');

        $averageRating = $products->avg('rating');


        /*
        |--------------------------------------------------------------------------
        | Category Statistics
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Rating Distribution
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Most Expensive Products
        |--------------------------------------------------------------------------
        */

        $expensiveProducts = $products
            ->sortByDesc('price')
            ->take(3)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Highest Rated Products
        |--------------------------------------------------------------------------
        */

        $topRatedProducts = $products
            ->prioritize(fn ($product) => $product['rating'] === 5)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Unique Product Tags
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Product Sorting
        |--------------------------------------------------------------------------
        */

        $sort = $request->get('sort', 'price_low');

        switch ($sort) {

            case 'price_high':

                $sortedProducts = $products
                    ->sortByDesc('price')
                    ->values();

                break;


            case 'rating_high':

                $sortedProducts = $products
                    ->sortByDesc('rating')
                    ->values();

                break;


            case 'name':

                $sortedProducts = $products
                    ->sortBy('name')
                    ->values();

                break;


            case 'price_low':
            default:

                $sortedProducts = $products
                    ->sortBy('price')
                    ->values();

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | Selected Products
        |--------------------------------------------------------------------------
        */

        $selectedIds = $request->input('products', []);

        $selectedIds = collect($selectedIds)
            ->map(fn ($id) => (int) $id);


        /*
        |--------------------------------------------------------------------------
        | Comparison Products
        |--------------------------------------------------------------------------
        */

        $comparisonProducts = $products
            ->filter(function ($product) use ($selectedIds) {

                return $selectedIds->contains($product['id']);
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Comparison Statistics
        |--------------------------------------------------------------------------
        */

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
     * Smart Product Recommendation Dashboard.
     */
    public function productRecommendations(Request $request)
    {
        $products = $this->products();


        /*
        |--------------------------------------------------------------------------
        | User Preferences
        |--------------------------------------------------------------------------
        */

        $selectedCategory = $request->get('category');

        $selectedTag = $request->get('tag');

        $minimumRating = (int) $request->get('rating', 0);


        /*
        |--------------------------------------------------------------------------
        | Start Recommendation Collection
        |--------------------------------------------------------------------------
        */

        $recommendations = $products;


        /*
        |--------------------------------------------------------------------------
        | Category Filtering
        |--------------------------------------------------------------------------
        */

        if ($selectedCategory) {

            $recommendations = $recommendations
                ->filter(function ($product) use ($selectedCategory) {

                    return $product['category'] === $selectedCategory;
                });
        }


        /*
        |--------------------------------------------------------------------------
        | Tag Filtering Using containsAny Macro
        |--------------------------------------------------------------------------
        */

        if ($selectedTag) {

            $recommendations = $recommendations
                ->filter(function ($product) use ($selectedTag) {

                    return collect($product['tags'])
                        ->containsAny([$selectedTag]);
                });
        }


        /*
        |--------------------------------------------------------------------------
        | Minimum Rating Filtering
        |--------------------------------------------------------------------------
        */

        if ($minimumRating > 0) {

            $recommendations = $recommendations
                ->filter(function ($product) use ($minimumRating) {

                    return $product['rating'] >= $minimumRating;
                });
        }


        /*
        |--------------------------------------------------------------------------
        | Prioritize Highly Rated Products
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations
            ->prioritize(fn ($product) => $product['rating'] === 5)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Weighted Featured Product
        |--------------------------------------------------------------------------
        */

        $featuredProduct = null;

        if ($recommendations->isNotEmpty()) {

            $featuredProduct = $recommendations
                ->weightedRandom('rating');
        }


        /*
        |--------------------------------------------------------------------------
        | Recommendation Score
        |--------------------------------------------------------------------------
        |
        | Rating contributes up to 100 points.
        | Each matching/product tag contributes 5 points.
        |
        */

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
}