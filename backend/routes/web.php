<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $shop = base_path('../frontend/dist/index.html');

    return is_file($shop) ? response()->file($shop) : redirect('/admin');
});

Route::get('/admin/orders/{order}/print-label', function (Order $order) {
    abort_unless(auth('web')->check(), 403);
    return view('admin.orders.print_label', ['order' => $order->load('items')]);
})->name('admin.orders.print-label')->middleware('auth:web');
