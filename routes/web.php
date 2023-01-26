<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/* ----------サブスクリプション決済---------- */

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* Route::get('/dashboard', function() {
    /* auth()->user()->subscription('default')->swap('price_1MTyugGGqLVGSXrfjMud05hP'); */
    /* return view('dashboard');
})->middleware(['auth'])->name('dashboard'); */

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/subscription', function() {
    return view('subscription', [
        'intent' => auth()->user()->createSetupIntent()
    ]);
})->middleware(['auth'])->name('subscription');

Route::post('/user/subscribe', function (Request $request) {
    $request->user()->newSubscription('default', 'price_1MTyugGGqLVGSXrfCqsEQSkY')
    /* ->trialDays(10) */
    ->create($request->paymentMethodId);

    return redirect('/dashboard');

})->middleware(['auth'])->name('subscribe.post');

Route::get('/basic', function() {
    return view('basic');
})->middleware(['auth', 'basic'])->name('basic');

Route::get('/premium', function() {
    return view('premium');
})->middleware(['auth', 'premium'])->name('premium');

/* ----------Single Charges決済---------- */

Route::get('/purchase', function() {
    return view('purchase');
})->middleware(['auth'])->name('purchase');

Route::post('/purchase', function(Request $request) {
    $request->user()->charge(
        100, $request->paymentMethodId
    );

    return redirect('/dashboard');

})->middleware(['auth'])->name('purchase.post');