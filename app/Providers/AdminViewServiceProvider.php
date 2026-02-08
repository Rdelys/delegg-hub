<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Client;
use App\Models\Licence;

class AdminViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('admin.*', function ($view) {
    $view->with([
        'clientsCount'  => Client::where('role', '!=', 'simpleutilisateur')->count(),
        'licencesCount' => Licence::count(),
    ]);
});

    }
}
