<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {    
    return response()->json([ "message" => "Laravel API TextFromAPI" ]);
});


Route::post('/register', [Controller::class, 'register']);
Route::post('/login', [Controller::class, 'verifyLogin']);
Route::get('/posts', [PostController::class, 'getPosts'] );
Route::get('/post/{post_id}', [PostController::class, 'getPost'] );
Route::get('/categories', [CategoryController::class, 'getCategories'] );
Route::get('/category/{cate_id}', [CategoryController::class, 'getCategory'] );
Route::get('/postBycate/{cateID}', [PostController::class, 'postBycate'] );


Route::middleware('auth:sanctum')->group( function(){

    //category
    Route::post('/category/create', [CategoryController::class, 'createCategory'] );
    Route::post('/category/update', [CategoryController::class, 'updateCategory'] );
    Route::post('/category/delete', [CategoryController::class, 'deleteCategory'] );

    //post
    Route::get('/mypost', [PostController::class, 'getMyPost'] ); 
    Route::post('/post/create', [PostController::class, 'createPost'] );
    Route::post('/post/editpost', [PostController::class, 'editPost'] );
    Route::post('/post/delete', [PostController::class, 'deletePost'] );

    //user
    Route::post('/user/updatepassword', [PostController::class, 'updatePassword'] );
    Route::post('/user/edit', [PostController::class, 'editUser'] );
    Route::get('/user/delete', [PostController::class, 'deleteUser'] );
    Route::get('/user', [Controller::class, 'user'] );
    Route::get('/logout', [Controller::class, 'logout']);
});

