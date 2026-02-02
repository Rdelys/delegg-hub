<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| CLIENT
|--------------------------------------------------------------------------
*/

// Page d’accueil = login client
Route::get('/', function () {
    return view('client.login');
});

// Traitement login client
Route::post('/login', function (Request $request) {

    if (
        $request->email === 'client@test.com' &&
        $request->password === '1234'
    ) {
        session(['client_logged' => true]);
        return redirect('/home');
    }

    return back()->with('error', 'Identifiants client incorrects');
});

// Home client (après connexion)
Route::get('/home', function () {

    if (!session('client_logged')) {
        return redirect('/');
    }

    return view('client.home');
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

    Route::get('/clients', function () {
        return view('admin.pages.clients');
    });

    Route::get('/licences', function () {
        return view('admin.pages.licences');
    });

});
