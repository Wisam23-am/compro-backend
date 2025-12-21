<?php

namespace App\Providers;

use App\Models\Principle;
use App\Models\Team;
use App\Policies\PrinciplePolicy;
use App\Policies\TeamPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Principle::class => PrinciplePolicy::class,
        Team::class => TeamPolicy::class,
    ];

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
        Model::unguard();

        // Register policies
        $this->registerPolicies();

        // Clear principle cache when a principle is saved or deleted
        Principle::saved(function () {
            \Cache::forget('principles.active');
            \Cache::forget('principles.stats');
        });

        Principle::deleted(function () {
            \Cache::forget('principles.active');
            \Cache::forget('principles.stats');
        });

        // Clear team cache when a team member is saved or deleted
        Team::saved(function () {
            \Cache::forget('team.active');
            \Cache::forget('team.stats');
        });

        Team::deleted(function () {
            \Cache::forget('team.active');
            \Cache::forget('team.stats');
        });
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
