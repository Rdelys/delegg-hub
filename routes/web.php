<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\LicenceController;
use App\Http\Controllers\Client\ClientAuthController;

/*
|--------------------------------------------------------------------------
| CLIENT
|--------------------------------------------------------------------------
*/

// Page d’accueil = login client
Route::get('/', [ClientAuthController::class, 'showLogin'])
    ->name('client.login');

Route::post('/login', [ClientAuthController::class, 'login'])
    ->name('client.login.submit');

Route::middleware('client')->group(function () {

    Route::get('/home', fn () => view('client.home'))->name('client.home');
    Route::get('/profil', fn () => view('client.profil'))->name('client.profil');
    Route::get('/insee', fn () => view('client.insee'))->name('client.insee');
    Route::get('/chambre-metiers', fn () => view('client.chambre-metiers'))->name('client.chambre');
    Route::get('/google', fn () => view('client.google'))->name('client.google');
    Route::get('/utilisateurs', fn () => view('client.utilisateurs'))->name('client.users');

    Route::post('/logout', [ClientAuthController::class, 'logout'])
        ->name('client.logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

// Page login admin
Route::get('/admin/login', function () {
    return view('admin.login');
});

Route::post('/admin/login', function (Request $request) {

    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $admin = Admin::where('email', $request->email)->first();

    if ($admin && Hash::check($request->password, $admin->password)) {
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect('/admin/pages/dashboard');
    }

    return back()->with('error', 'Identifiants incorrects');
});


// Vérification login admin
Route::middleware('admin')->group(function () {
    Route::get('/admin/pages/dashboard', function () {
        return view('admin.pages.dashboard');
    });


    Route::post('/admin/logout', function (Request $request) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    });
});

Route::middleware('admin')->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    });

        Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('admin.clients.store');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('admin.clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('admin.clients.destroy');


    Route::get('/licences', [LicenceController::class, 'index'])
        ->name('admin.licences.index');

    Route::post('/licences', [LicenceController::class, 'store'])
        ->name('admin.licences.store');

    Route::delete('/licences/{licence}', [LicenceController::class, 'destroy'])
        ->name('admin.licences.destroy');


});