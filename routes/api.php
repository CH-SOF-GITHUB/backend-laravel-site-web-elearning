<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\LanguageMediumController;
use App\Http\Middleware\CorsMiddleware;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// API routes for handling API requests
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
    // API STRIPE CHECKOUT
    Route::post('/create-checkout-session', function (Request $request) {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $request->course_title,
                    ],
                    'unit_amount' => $request->price * 100, // En centimes
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => env('FRONT_URL') . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => env('FRONT_URL') . '/cancel',
        ]);

        return response()->json(['id' => $session->id]);
    })->middleware('auth:api')->name('stripe.initiate');

    // API STRIPE VERIFY PAYMENT AND UPDATE ENROLLMENT STATUS
    Route::get('/verify-payment', function (Request $request) {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = Session::retrieve($request->session_id);

        if ($session->payment_status === 'paid') {
            // Récupérer l'inscription de l'utilisateur
            $user = Auth::user(); // Récupérer l'utilisateur authentifié
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('status', 'draft') // Assurez-vous de cibler l'inscription correcte
                ->first();

            if ($enrollment) {
                // Mettre à jour le statut d'inscription
                $enrollment->status = 'validated';
                $enrollment->save(); // Enregistrer les modifications
            }
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed']);
    })->middleware('auth:api')->name('stripe.verify');


    // API routes for handling API requests
    Route::middleware('auth:api')->prefix('user')->group(function () {
        // Récupère tous les languges pour un cours
        Route::get('/list_language_mediums', [LanguageMediumController::class, 'index']);
        // Récupère toutes les cours GET
        Route::get('enrolled_courses', [EnrollmentController::class, 'allEnrolls']);
        // Récupère toutes les cours par utilisateur GET
        Route::get('enrollments', [EnrollmentController::class, 'allEnrollsByUser']);
        // Récupération enroll by course
        Route::prefix('courses/{courseId}')->group(function () {
            // Récupère tous les commentaires pour un cours
            Route::get('comments', [CommentController::class, 'index']);
            // Ajouter un commentaire à un cours
            Route::post('comments', [CommentController::class, 'store']);
            // Supprimer un commentaire spécifique
            Route::delete('comments/{commentId}', [CommentController::class, 'destroy']);
            // Inscription à un cours GET 
            Route::get('enroll', [EnrollmentController::class, 'index'])->where('courseId', '[0-9]+');
            // Inscription à un cours POST
            Route::post('enroll', [EnrollmentController::class, 'store']);
            // Récupération enroll by id pour un cours
            Route::get('/enroll/{enrollmentId}', [EnrollmentController::class, 'show'])->where('enrollmentId', '[0-9]+');
            // supprimer enroll by id pour un cours
            Route::delete('/enroll/{enrollmentId}', [EnrollmentController::class, 'destroy'])->where('enrollmentId', '[0-9]+');;
            // update status enrollement by id
            Route::put('/enroll/{id}/status', [EnrollmentController::class, 'updateStatus']);
        });
        Route::get('categories', [CategoryController::class, 'index']); // Specify method
        Route::get('courses', [FormationController::class, 'index']);
        Route::get('courses/{courseId}', [FormationController::class, 'show']);
        Route::get('inscriptions', [InscriptionController::class, 'index']); // Specify method
        Route::get('books', [BookController::class, 'index']); // Specify method
    });

    Route::middleware('auth:api')->prefix('admin')->group(function () {
        Route::prefix('courses/{courseId}')->group(function () {
            // Récupère tous les commentaires pour un cours
            Route::get('comments', [CommentController::class, 'index']);

            // Ajouter un commentaire à un cours
            Route::post('comments', [CommentController::class, 'store']);

            // Supprimer un commentaire spécifique
            Route::delete('comments/{commentId}', [CommentController::class, 'destroy']);

            // afficher un commentaire par id 
            Route::get('comments/{commentId}', [CommentController::class, 'show']);
            // Supprimer un commentaire spécifique
            Route::delete('comments/{commentId}', [CommentController::class, 'destroy']);
            // Inscription à un cours GET
            Route::get('enroll', [EnrollmentController::class, 'index'])->where('courseId', '[0-9]+');
            // Inscription à un cours POST
            Route::post('enroll', [EnrollmentController::class, 'store']);
            // Récupération enroll by id pour un cours
            Route::get('enroll/{enrollmentId}', [EnrollmentController::class, 'show'])->where('enrollmentId', '[0-9]+');
            // supprimer enroll by id pour un cours
            Route::delete('enroll/{enrollmentId}', [EnrollmentController::class, 'destroy'])->where('enrollmentId', '[0-9]+');
        });
        Route::resource('categories', CategoryController::class);
        Route::resource('courses', FormationController::class); // Specify method
        Route::resource('inscriptions', InscriptionController::class);
        Route::resource('books', BookController::class);
    });
});
