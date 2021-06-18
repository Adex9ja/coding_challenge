<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post("/snippets","Snippet\SnippetController@createSnippet")->name("createSnippet");
Route::get("/snippets/{name}","Snippet\SnippetController@getSnippet")->name("getSnippet");

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
