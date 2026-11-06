<?php

namespace App\Providers;

use App\Services\BcvRateService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view): void {
            if (! array_key_exists('bcvRate', $view->getData())) {
                $view->with('bcvRate', BcvRateService::getRate());
            }
        });
    }
}
