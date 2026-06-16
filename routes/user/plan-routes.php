<?php

use App\Http\Controllers\User\UserPlanController;
use Illuminate\Support\Facades\Route;

// Legacy investment-plan pages retired — citizens invest in assets. Route
// names are kept (as redirects) so any lingering route() references resolve.
Route::middleware(['auth:sanctum', 'verified', 'complete.kyc'])->prefix('plans')->name('user.plans.')->group(function () {
    Route::get('/', fn () => redirect()->route('invest.index'))->name('index');
    Route::get('/my-plans', fn () => redirect()->route('investments.mine'))->name('my-plans');
    Route::get('/details/{userPlan}', fn () => redirect()->route('investments.mine'))->name('details');
    Route::get('/payment/{userPlan}', fn () => redirect()->route('investments.mine'))->name('payment');
    Route::get('/contract/{userPlan}', fn () => redirect()->route('investments.mine'))->name('contract');
    Route::get('/{plan}/invest', fn () => redirect()->route('invest.index'))->name('invest');
    Route::get('/{plan}', fn () => redirect()->route('invest.index'))->name('show');

    // Legacy POST endpoints (no UI links to them) — redirect harmlessly.
    Route::post('/{plan}/invest', fn () => redirect()->route('invest.index'))->name('process-investment');
    Route::post('/payment/{userPlan}', fn () => redirect()->route('investments.mine'))->name('process-payment');
    Route::post('/cancel/{userPlan}', fn () => redirect()->route('investments.mine'))->name('cancel');
});

