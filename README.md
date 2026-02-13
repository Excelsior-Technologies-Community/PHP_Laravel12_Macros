# PHP_Laravel12_Macros

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.x-blue)
![Spatie](https://img.shields.io/badge/Spatie-Collection--Macros-green)
![License](https://img.shields.io/badge/License-MIT-lightgrey)

---

## Overview

This project demonstrates the usage of:

* Laravel 12
* Spatie laravel-collection-macros
* Collection-based filtering
* Pagination on collections
* Advanced filtering logic
* Real-world ecommerce-style filtering examples

The project showcases how powerful Laravel collections become when extended using Spatie macros.

---

## Features

* Basic macro usage examples
* Collection pagination
* Macro playground demo
* Ecommerce-style filtering
* Tag-based filtering using `containsAny` and `containsAll`
* Rating prioritization
* Section grouping
* Weighted random product selection

---

## Folder Structure

```
PHP_Laravel12_Macros/
│
├── routes/
│   └── web.php
│
├── resources/
│   └── views/
│       ├── macro-test.blade.php
│       ├── paginate-test.blade.php
│       ├── macro-playground.blade.php
│       ├── shop-demo.blade.php
│       └── shop-filter.blade.php
│
└── .env
```

---

# Project Setup Guide

## Step 1 – Project Installation

### 1. Create New Laravel 12 Project

```bash
composer create-project laravel/laravel PHP_Laravel12_Macros
```

### .env Configuration

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Start Development Server

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

---

## Step 2 – Install Spatie Collection Macros

Install the package:

```bash
composer require spatie/laravel-collection-macros
```

---

## Step 3 – Project Routes

All routes are defined in:

```
routes/web.php
```

The project includes the following demo routes:

* `/macro-test` – Basic Spatie macro demonstration
* `/paginate-test` – Collection pagination demo
* `/macro-playground` – Multiple macro examples
* `/shop-demo` – Ecommerce-style macro usage
* `/shop-filter` – Advanced filtering logic using macros

```
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
```

---

## Step 4 – Blade Files

Blade files are located in:

```
resources/views/
```

Included Views:

## resources/views/macro-test.blade.php

```html
<!DOCTYPE html>
<html>
<head>
    <title>Laravel Collection Macros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .card {
            background: #f9fafb;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 5px solid #4f46e5;
        }

        .key {
            font-weight: bold;
            color: #4f46e5;
        }

        .value {
            float: right;
            color: #111827;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1> Laravel Collection Macros Output</h1>

    @foreach($data as $key => $value)
        <div class="card">
            <span class="key">{{ $key }}</span>
            <span class="value">
                {{ is_bool($value) ? ($value ? 'true' : 'false') : $value }}
            </span>
            <div style="clear: both;"></div>
        </div>
    @endforeach

    <div class="footer">
        Powered by Spatie Collection Macros 
    </div>
</div>

</body>
</html>

```

## resources/views/paginate-test.blade.php

```html
<!DOCTYPE html>
<html>
<head>
    <title>Pagination Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center mb-4"> Collection Pagination</h2>

        <ul class="list-group mb-3">
            @foreach($items as $item)
                <li class="list-group-item">
                    Item {{ $item }}
                </li>
            @endforeach
        </ul>

        <div class="d-flex justify-content-center">
            {{ $items->links() }}
        </div>
    </div>
</div>

</body>
</html>

```

## resources/views/macro-playground.blade.php

```html
<!DOCTYPE html>
<html>
<head>
    <title>Macro Playground</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center mb-4"> Macro Playground</h2>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Macro</th>
                    <th>Output</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>
                            @if($value instanceof \Illuminate\Support\Collection)
                                <pre>{{ $value->toJson(JSON_PRETTY_PRINT) }}</pre>
                            @elseif(is_array($value))
                                <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                            @elseif(is_bool($value))
                                {{ $value ? 'true' : 'false' }}
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

</body>
</html>

```

## resources/views/shop-demo.blade.php

```html
<!DOCTYPE html>
<html>
<head>
    <title>Shop Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4"> Macro Powered Shop Demo</h2>

    <div class="card p-3 mb-4">
        <h4>Summer Products (containsAny)</h4>
        <pre>{{ $summerProducts->toJson(JSON_PRETTY_PRINT) }}</pre>
    </div>

    <div class="card p-3 mb-4">
        <h4>Denim + Winter (containsAll)</h4>
        <pre>{{ $denimWinter->toJson(JSON_PRETTY_PRINT) }}</pre>
    </div>

    <div class="card p-3 mb-4">
        <h4>Prioritized (Rating 5 First)</h4>
        <pre>{{ $prioritized->toJson(JSON_PRETTY_PRINT) }}</pre>
    </div>

    <div class="card p-3 mb-4">
        <h4>Section By Category</h4>
        <pre>{{ $sections->toJson(JSON_PRETTY_PRINT) }}</pre>
    </div>

    <div class="card p-3 mb-4">
        <h4>Name & Price (pluckMany)</h4>
        <pre>{{ $nameAndPrice->toJson(JSON_PRETTY_PRINT) }}</pre>
    </div>

    <div class="card p-3 mb-4">
        <h4> Random Featured (weightedRandom)</h4>
        <pre>{{ json_encode($randomFeatured, JSON_PRETTY_PRINT) }}</pre>
    </div>

</div>

</body>
</html>

```

## resources/views/shop-filter.blade.php

```html
<!DOCTYPE html>
<html>
<head>
    <title>Shop Filter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h2 class="text-center mb-4"> Live Shop Filtering (Macros)</h2>

    <form method="GET" class="row g-3 mb-4">

        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <option value="Clothing">Clothing</option>
                <option value="Footwear">Footwear</option>
                <option value="Accessories">Accessories</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="tag" class="form-select">
                <option value="">All Tags</option>
                <option value="summer">Summer</option>
                <option value="winter">Winter</option>
                <option value="sports">Sports</option>
                <option value="luxury">Luxury</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="rating" class="form-select">
                <option value="">Min Rating</option>
                <option value="3">3+</option>
                <option value="4">4+</option>
                <option value="5">5</option>
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

    </form>

    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card p-3 shadow-sm">
                    <h5>{{ $product['name'] }}</h5>
                    <p>Category: {{ $product['category'] }}</p>
                    <p>Price:  {{ $product['price'] }}</p>
                    <p>Rating:  {{ $product['rating'] }}</p>
                    <p>Tags: {{ implode(', ', $product['tags']) }}</p>
                </div>
            </div>
        @empty
            <div class="alert alert-warning">
                No products found.
            </div>
        @endforelse
    </div>

</div>

</body>
</html>

```

---

# Final Output Routes

## 1. Macro Basic Test

URL:

```
http://127.0.0.1:8000/macro-test
```
<img width="642" height="366" alt="Screenshot 2026-02-13 113715" src="https://github.com/user-attachments/assets/ce1461ab-4806-46eb-b414-db370bc577b6" />

---

## 2. Pagination Test

URL:

```
http://127.0.0.1:8000/paginate-test
```
<img width="1077" height="517" alt="Screenshot 2026-02-13 113703" src="https://github.com/user-attachments/assets/9d9b401f-6e9c-4413-8cb3-20bc189cefad" />

---

## 3. Macro Playground

URL:

```
http://127.0.0.1:8000/macro-playground
```
<img width="876" height="901" alt="Screenshot 2026-02-13 113850" src="https://github.com/user-attachments/assets/43dbcdc4-9ec0-42d6-8d60-7547120dd54e" />

---

## 4. Shop Demo

URL:

```
http://127.0.0.1:8000/shop-demo
```
<img width="450" height="647" alt="Screenshot 2026-02-13 114731" src="https://github.com/user-attachments/assets/ce276d9a-999d-4032-b734-581a61b5e254" />

<img width="438" height="736" alt="Screenshot 2026-02-13 114751" src="https://github.com/user-attachments/assets/2928945e-0251-4c9c-b0b3-3dcec0f16ff9" />

---

## 5. Shop Filter

URL:

```
http://127.0.0.1:8000/shop-filter
```
<img width="1429" height="718" alt="Screenshot 2026-02-13 114117" src="https://github.com/user-attachments/assets/34a8b963-2bc1-4cdc-be31-5f8f890035d4" />

---


