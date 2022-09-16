<?php

namespace App\Providers ;

use Illuminate\Support\Facades\Gate ;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider ;
use App\User ;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Admin ACL
        Gate::define('isAdmin' , function(User $user) {
            return $user->role == 'Admin' ;
        }) ;
        // Writer ACL
        Gate::define('isWriter' , function(User $user){
            return $user->role == 'Writer' ;
        }) ;
    }
}
