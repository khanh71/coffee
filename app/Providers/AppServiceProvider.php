<?php

namespace App\Providers;

use App\Shop;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('app', function ($view) {
            $shop = Shop::where('idshop', Auth::user()->shopid)->first();
            $eee = User::where('id', Auth::user()->id)->first();
            $view->with(['shop' => $shop, 'eee' => $eee]);
        });
    }
}
