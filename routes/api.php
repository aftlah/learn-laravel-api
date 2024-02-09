<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\PostController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route Group
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);

    Route::post('posts', [PostController::class, 'store']);
    Route::patch('posts/{post}', [PostController::class,'update'])->middleware('postOwner');
    Route::delete('posts/{post}', [PostController::class,'destroy'])->middleware('postOwner');


    // untuk menetahui siapa yang sedah login
    Route::get('me', [AuthController::class, 'me']);
});


Route::controller(PostController::class)->group(function () {
    Route::get('posts', 'index');
    Route::get('posts/{post}', 'show');
});
// Route::apiResource('posts', PostController::class);


Route::post('login', [AuthController::class, 'login']);


