<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('manage-users', function ($user) {
            return $user->hasRole('admin');
        });
        
        Gate::define('manage-forms', function ($user) {
            return $user->hasRole('jamut');
        });

        Gate::define('manage-results', function ($user) {
            return $user->hasRole('jamut');
        });
        
        Gate::define('fill-forms', function ($user) {
            return $user->hasRole('prodi');
        });
        
        Gate::define('view-audited', function ($user) {
            return $user->hasRole('prodi');
        });
        
        Gate::define('complete-forms', function ($user) {
            return $user->hasRole('gkm');
        });

        Gate::define('audit-forms', function ($user) {
            return $user->hasRole('auditor');
        });
    }
}
