<?php
// app/Providers/AuthServiceProvider.php
namespace App\Providers;

use App\Models\Chantier;
use App\Policies\ChantierPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Chantier::class => \App\Policies\ChantierPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies(); // Ajout de cette ligne critique

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