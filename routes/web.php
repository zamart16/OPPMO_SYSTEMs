<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnduserController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\HeadController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', fn() => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin
Route::middleware(['auth','role:administrator'])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users/inactive', [AdminController::class, 'getInactiveUsers']);
    Route::post('/admin/users/{id}/activate', [AdminController::class, 'activateUser']);
});



// End user
Route::middleware(['auth','role:end_user'])->group(function () {
    Route::get('/end-user', [EnduserController::class,'dashboard'])->name('end-user.dashboard');
});





// Evaluation routes
Route::middleware(['auth'])->group(function () {

    Route::post('/evaluation/store', [EvaluationController::class, 'store'])
        ->name('evaluation.store');

    Route::get('/evaluation/list', [EvaluationController::class, 'list'])
        ->name('evaluation.list');

    Route::delete('/evaluation/{id}', [EvaluationController::class, 'destroy'])
        ->name('evaluation.destroy');

    Route::get('/evaluations/{id}', [EvaluationController::class, 'show']);
    Route::get('/showupdateevaluations/{id}', [EvaluationController::class, 'show']);

    Route::put('/updateevaluations/{id}', [EvaluationController::class, 'update'])
    ->name('evaluation.update');

    Route::get('/evaluations/summary/download', [EvaluationController::class, 'downloadSummary'])->name('evaluations.summary.download');
    Route::get('/evaluations/{id}/download', [EvaluationController::class, 'download'])->name('evaluations.download');
});






Route::post('/evaluation/update/{token}', [HeadController::class, 'updateEvaluation'])->name('evaluation.update');

Route::get('/evaluation/head-review/{token}', [HeadController::class, 'reviewPage']);

Route::get('/evaluation/review/{token}', [HeadController::class, 'reviewEvaluation'])->name('evaluation.review');
























