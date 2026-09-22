<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MacroController;


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Existing Macro Test
|--------------------------------------------------------------------------
*/

Route::get('/macro-test', function () {

    $collection = collect([10, 20, 30, 40]);

    $data = [
        'second' => $collection->second(),
        'after_20' => $collection->after(20),
        'before_30' => $collection->before(30),
        'none_greater_than_100' => $collection->none(
            fn ($item) => $item > 100
        ),
    ];

    return view('macro-test', compact('data'));
});


/*
|--------------------------------------------------------------------------
| Existing Pagination Test
|--------------------------------------------------------------------------
*/

Route::get('/paginate-test', function () {

    $items = collect(range(1, 50))
        ->paginate(10);

    return view('paginate-test', compact('items'));
});


/*
|--------------------------------------------------------------------------
| Existing Macro Playground
|--------------------------------------------------------------------------
*/

Route::get('/macro-playground', function () {

    $collection = collect([1, 2, 3, 4, 5, 6]);

    $data = [
        'at_2' => $collection->at(2),
        'third' => $collection->third(),
        'fifth' => $collection->fifth(),
        'tail' => $collection->tail(),
        'rotate_1' => $collection->rotate(1),
        'containsAny' => $collection->containsAny([4, 10]),
        'containsAll' => $collection->containsAll([1, 2]),
        'none_gt_10' => $collection->none(
            fn ($item) => $item > 10
        ),
        'eachCons_2' => $collection->eachCons(2),
        'getNth_6' => $collection->getNth(6),
    ];

    return view('macro-playground', compact('data'));
});


/*
|--------------------------------------------------------------------------
| Shop Demo
|--------------------------------------------------------------------------
*/

Route::get('/shop-demo', function () {

    $products = collect([
        [
            'id' => 1,
            'name' => 'Shirt',
            'category' => 'Clothing',
            'price' => 1200,
            'tags' => ['cotton', 'summer'],
            'rating' => 4
        ],
        [
            'id' => 2,
            'name' => 'Jeans',
            'category' => 'Clothing',
            'price' => 2500,
            'tags' => ['denim', 'winter'],
            'rating' => 5
        ],
        [
            'id' => 3,
            'name' => 'Shoes',
            'category' => 'Footwear',
            'price' => 3000,
            'tags' => ['sports', 'running'],
            'rating' => 3
        ],
        [
            'id' => 4,
            'name' => 'Watch',
            'category' => 'Accessories',
            'price' => 5000,
            'tags' => ['luxury'],
            'rating' => 5
        ],
    ]);

    $summerProducts = $products->filter(
        fn ($p) => collect($p['tags'])
            ->containsAny(['summer'])
    );

    $denimWinter = $products->filter(
        fn ($p) => collect($p['tags'])
            ->containsAll(['denim', 'winter'])
    );

    $prioritized = $products
        ->prioritize(fn ($p) => $p['rating'] === 5)
        ->values();

    $sections = $products
        ->sectionBy('category');

    $nameAndPrice = $products
        ->pluckMany(['name', 'price']);

    $randomFeatured = $products
        ->weightedRandom('rating');

    return view('shop-demo', compact(
        'summerProducts',
        'denimWinter',
        'prioritized',
        'sections',
        'nameAndPrice',
        'randomFeatured'
    ));
});


/*
|--------------------------------------------------------------------------
| NEW: Advanced Shop Filter
|--------------------------------------------------------------------------
|
| 1. Search
| 2. Category
| 3. Multiple tags
| 4. Minimum price
| 5. Maximum price
| 6. Sorting
| 7. Pagination
|
*/

Route::get(
    '/shop-filter',
    [MacroController::class, 'shopFilter']
)->name('shop.filter');


/*
|--------------------------------------------------------------------------
| NEW: Wishlist
|--------------------------------------------------------------------------
*/

Route::post(
    '/wishlist/{id}',
    [MacroController::class, 'addToWishlist']
)->name('wishlist.add');


Route::delete(
    '/wishlist/{id}',
    [MacroController::class, 'removeFromWishlist']
)->name('wishlist.remove');


/*
|--------------------------------------------------------------------------
| NEW: Export CSV
|--------------------------------------------------------------------------
*/

Route::get(
    '/shop-filter/export/csv',
    [MacroController::class, 'exportCsv']
)->name('shop.export.csv');


/*
|--------------------------------------------------------------------------
| NEW: Export JSON
|--------------------------------------------------------------------------
*/

Route::get(
    '/shop-filter/export/json',
    [MacroController::class, 'exportJson']
)->name('shop.export.json');


/*
|--------------------------------------------------------------------------
| Product Analytics
|--------------------------------------------------------------------------
*/

Route::get(
    '/product-analytics',
    [MacroController::class, 'productAnalytics']
)->name('product.analytics');


/*
|--------------------------------------------------------------------------
| Product Comparison
|--------------------------------------------------------------------------
*/

Route::get(
    '/product-comparison',
    [MacroController::class, 'productComparison']
)->name('product.comparison');


/*
|--------------------------------------------------------------------------
| Product Recommendations
|--------------------------------------------------------------------------
*/

Route::get(
    '/product-recommendations',
    [MacroController::class, 'productRecommendations']
)->name('product.recommendations');
