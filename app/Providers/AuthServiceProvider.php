<?php

namespace App\Providers;

use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Photo;
use App\Models\Evaluation;
use App\Policies\ChantierPolicy;
use App\Policies\DevisPolicy;
use App\Policies\FacturePolicy;
use App\Policies\PhotoPolicy;
use App\Policies\EvaluationPolicy;
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
        Chantier::class => ChantierPolicy::class,
        Devis::class => DevisPolicy::class,
        Facture::class => FacturePolicy::class,
        Photo::class => PhotoPolicy::class,
        Evaluation::class => EvaluationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
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

        Gate::define('access-admin-panel', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('view-financial-data', function ($user, $chantier = null) {
            if ($user->isAdmin()) {
                return true;
            }
            
            if ($user->isCommercial() && $chantier) {
                return $chantier->commercial_id === $user->id;
            }
            
            return false;
        });

        Gate::define('manage-enterprise-settings', function ($user) {
            return $user->isAdmin();
        });
    }
}