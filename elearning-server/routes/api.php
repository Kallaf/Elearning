<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstructorsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('jwt.customAuth')->get('/current-instructor', [InstructorsController::class, 'getCurrentInstructor']);

Route::resource('/posts', 'App\Http\Controllers\PostsController');

Route::post('/instructors', [InstructorsController::class, 'register']);