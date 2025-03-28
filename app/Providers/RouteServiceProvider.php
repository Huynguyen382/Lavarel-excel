<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /*
    @var string|null;
    */
    protected $namespace = 'App\Http\Controllers';
    public function boot()
    {
        parent::boot();

        $this -> routes(function() {
            Route::middleware('web') -> group(base_path('routes/web.php'));
            Route ::middleware('api') -> prefix('api') ->group(base_path('routes/api.php'));  
        });
    }
}
