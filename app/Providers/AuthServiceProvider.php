<?php
// app/Providers/AuthServiceProvider.php
namespace App\Providers;

use App\Models\Chantier;
use App\Policies\ChantierPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Chantier::class => ChantierPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Gates personnalisés
        Gate::define('admin-only', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('commercial-or-admin', function ($user) {
            return $user->isCommercial() || $user->isAdmin();
        });

        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });
    }
}