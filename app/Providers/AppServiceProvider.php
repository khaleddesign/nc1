<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\TailwindHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Enregistrer le helper Tailwind comme singleton
        $this->app->singleton('tailwind', function () {
            return new TailwindHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configuration supplémentaire si nécessaire
    }
}