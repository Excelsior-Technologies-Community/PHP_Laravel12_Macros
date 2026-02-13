<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Route
Route::get('/', function () {
    return view('welcome');
});


// Macro Basic Test Route - Demonstrates basic Spatie macros
Route::get('/macro-test', function () {

    $collection = collect([10, 20, 30, 40]);

    $data = [
        'second' => $collection->second(),
        'after_20' => $collection->after(20),
        'before_30' => $collection->before(30),
        'none_greater_than_100' => $collection->none(fn($item) => $item > 100),
    ];

    return view('macro-test', compact('data'));
});


// Pagination Test Route - Demonstrates collection pagination
Route::get('/paginate-test', function () {

    $items = collect(range(1, 50))->paginate(10);

    return view('paginate-test', compact('items'));
});


// Macro Playground Route - Demonstrates multiple macros
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
        'none_gt_10' => $collection->none(fn($item) => $item > 10),
        'eachCons_2' => $collection->eachCons(2),
        'getNth_6' => $collection->getNth(6),
    ];

    return view('macro-playground', compact('data'));
});


// Shop Demo Route - Demonstrates ecommerce-style macro usage
Route::get('/shop-demo', function () {

    $products = collect([
        ['id'=>1,'name'=>'Shirt','category'=>'Clothing','price'=>1200,'tags'=>['cotton','summer'],'rating'=>4],
        ['id'=>2,'name'=>'Jeans','category'=>'Clothing','price'=>2500,'tags'=>['denim','winter'],'rating'=>5],
        ['id'=>3,'name'=>'Shoes','category'=>'Footwear','price'=>3000,'tags'=>['sports','running'],'rating'=>3],
        ['id'=>4,'name'=>'Watch','category'=>'Accessories','price'=>5000,'tags'=>['luxury'],'rating'=>5],
    ]);

    $summerProducts = $products->filter(fn($p) =>
        collect($p['tags'])->containsAny(['summer'])
    );

    $denimWinter = $products->filter(fn($p) =>
        collect($p['tags'])->containsAll(['denim','winter'])
    );

    $prioritized = $products->prioritize(fn($p) => $p['rating'] === 5)->values();

    $sections = $products->sectionBy('category');

    $nameAndPrice = $products->pluckMany(['name','price']);

    $randomFeatured = $products->weightedRandom('rating');

    return view('shop-demo', compact(
        'summerProducts',
        'denimWinter',
        'prioritized',
        'sections',
        'nameAndPrice',
        'randomFeatured'
    ));
});


// Shop Filter Route - Demonstrates filtering logic using macros
Route::get('/shop-filter', function (Request $request) {

    $products = collect([
        ['id'=>1,'name'=>'Shirt','category'=>'Clothing','price'=>1200,'tags'=>['cotton','summer'],'rating'=>4],
        ['id'=>2,'name'=>'Jeans','category'=>'Clothing','price'=>2500,'tags'=>['denim','winter'],'rating'=>5],
        ['id'=>3,'name'=>'Shoes','category'=>'Footwear','price'=>3000,'tags'=>['sports','running'],'rating'=>3],
        ['id'=>4,'name'=>'Watch','category'=>'Accessories','price'=>5000,'tags'=>['luxury'],'rating'=>5],
        ['id'=>5,'name'=>'Jacket','category'=>'Clothing','price'=>4000,'tags'=>['winter'],'rating'=>4],
    ]);

    $filtered = $products;

    if ($request->category) {
        $filtered = $filtered->where('category', $request->category);
    }

    if ($request->tag) {
        $filtered = $filtered->filter(fn($p) =>
            collect($p['tags'])->containsAny([$request->tag])
        );
    }

    if ($request->rating) {
        $filtered = $filtered->where('rating', '>=', (int)$request->rating);
    }

    $filtered = $filtered->prioritize(fn($p) => $p['rating'] === 5)->values();

    return view('shop-filter', [
        'products' => $filtered,
        'filters' => $request->all()
    ]);
});
