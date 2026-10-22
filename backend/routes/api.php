<?php

use App\Models\Banner;
use App\Models\Category;
use App\Models\FlavorNote;
use App\Models\Origin;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Storefront API endpoints
Route::prefix('storefront')->group(function () {
    // Banners
    Route::get('/banners', function () {
        return response()->json(Banner::active()->orderBy('sort_order')->get());
    });

    // Categories
    Route::get('/categories', function () {
        return response()->json(Category::where('is_active', true)->orderBy('sort_order')->get());
    });

    // Origins & Flavors for filter
    Route::get('/filter-options', function () {
        return response()->json([
            'origins' => Origin::orderBy('name')->get(),
            'flavor_notes' => FlavorNote::orderBy('name')->get(),
            'roast_levels' => ['light', 'medium', 'dark'],
        ]);
    });

    // Products (katalog dengan filter & relasi)
    Route::get('/products', function (Request $request) {
        $query = Product::with(['category', 'coffeeProfile.origin', 'flavorNotes', 'variants', 'images'])
            ->where('status', 'published');

        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        if ($request->has('roast_level') && $request->roast_level) {
            $query->whereHas('coffeeProfile', function ($q) use ($request) {
                $q->where('roast_level', $request->roast_level);
            });
        }

        if ($request->has('origin_id') && $request->origin_id) {
            $query->whereHas('coffeeProfile', function ($q) use ($request) {
                $q->where('origin_id', $request->origin_id);
            });
        }

        $products = $query->latest()->get();

        return response()->json($products);
    });

    // Posts (Our Story / Journal)
    Route::get('/posts', function () {
        return response()->json(
            Post::where('status', 'published')
                ->latest('published_at')
                ->take(6)
                ->get()
        );
    });
});
