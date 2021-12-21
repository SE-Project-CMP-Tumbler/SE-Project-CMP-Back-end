<?php

namespace App\Providers;

use App\Models\Blog;
use App\Policies\BlogPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //Blog::class => BlogPolicy::class,
        'App\Models\Blog' => 'App\Policies\BlogPolicy',
        'App\Models\Post' => 'App\Policies\PostPolicy',
        'App\Models\Tag' => 'App\Policies\TagPolicy',
        'App\Models\Block' => 'App\Policies\BlockPolicy'
        //'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        //
    }
}
