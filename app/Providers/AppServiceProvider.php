<?php

namespace App\Providers;

use App\Services\SiteService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SiteService::class, SiteService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (App::isLocal()) {
            DB::enableQueryLog();
        }
    }
}
