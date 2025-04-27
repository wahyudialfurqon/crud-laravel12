<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

//routes dengan mode research
Route::resource('/products', ProductController::class);

Route::get('/', function () {
    return view('welcome');
});
