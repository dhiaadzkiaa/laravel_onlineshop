<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Route::apiResource('products', ProductController::class);

// Routes for Product
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);


// Routes for Category
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/category/{id}', [CategoryController::class, 'show']);


// Routes for Brand
Route::get('/brands', [BrandsController::class, 'index']);
Route::get('/brand/{id}', [BrandsController::class, 'show']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Example of protected route with Sanctum (if needed)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/brands', [BrandsController::class, 'store']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::put('/brand/{id}', [BrandsController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
    Route::delete('/brand/{id}', [BrandsController::class, 'destroy']);
});



// Sanctum protected routes (if needed in the future)
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
// });

