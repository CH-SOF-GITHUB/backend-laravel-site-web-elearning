<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\LanguageMediumController;
use App\Http\Middleware\CorsMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group([
    // Add CorsMiddleware here for all routes in this group
    'middleware' => ['api', CorsMiddleware::class],
    'prefix' => 'learning'
], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::resource('courses', FormationController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('/list_language_mediums', [LanguageMediumController::class, 'index'])->middleware('auth:api')->name('api.languages');;
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('api.logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('api.refresh');
    Route::post('/profile', [AuthController::class, 'me'])->middleware('auth:api')->name('api.me');

    Route::middleware('auth:api')->prefix('user')->group(function () {
        Route::prefix('courses/{courseId}')->group(function() {
            // Récupère tous les commentaires pour un cours
            Route::get('comments', [CommentController::class, 'index']);
            // Ajouter un commentaire à un cours
            Route::post('comments', [CommentController::class, 'store']);      
            // Supprimer un commentaire spécifique
            Route::delete('comments/{commentId}', [CommentController::class, 'destroy']);
        });
        Route::get('categories', [CategoryController::class, 'index']); // Specify method
        Route::get('courses', [FormationController::class, 'index']);
        Route::get('inscriptions', [InscriptionController::class, 'index']); // Specify method
        Route::get('books', [BookController::class, 'index']); // Specify method
    });

    Route::middleware('auth:api')->prefix('admin')->group(function () {
        Route::prefix('courses/{courseId}')->group(function() {
            // Récupère tous les commentaires pour un cours
            Route::get('comments', [CommentController::class, 'index']);
        
            // Ajouter un commentaire à un cours
            Route::post('comments', [CommentController::class, 'store']);
        
            // Supprimer un commentaire spécifique
            Route::delete('comments/{commentId}', [CommentController::class, 'destroy']);
            
            // afficher un commentaire par id 
            Route::get('comments/{commentId}', [CommentController::class, 'show']);
        });
        Route::resource('categories', CategoryController::class);
        Route::resource('courses', FormationController::class); // Specify method
        Route::resource('inscriptions', InscriptionController::class);
        Route::resource('books', BookController::class);
    });
});
