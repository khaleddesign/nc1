<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\TailwindHelper;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Enregistrer le helper Tailwind comme singleton
        $this->app->singleton('tailwind', function () {
            return new TailwindHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Vous pouvez ajouter des configurations supplémentaires ici si nécessaire
    }
}