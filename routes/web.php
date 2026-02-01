<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

// Login admin
Route::get('/admin/login', function () {
    return view('admin.login');
});

// Traitement login admin
Route::post('/admin/login', function (Request $request) {

    if (
        $request->email === 'admin@test.com' &&
        $request->password === 'admin123'
    ) {
        session(['admin_logged' => true]);
        return redirect('/admin/dashboard');
    }

    return back()->with('error', 'Identifiants admin incorrects');
});

// Dashboard admin
Route::get('/admin/dashboard', function () {

    if (!session('admin_logged')) {
        return redirect('/admin/login');
    }

    return view('admin.dashboard');
});
