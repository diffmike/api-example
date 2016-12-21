<?php

namespace App\Providers;

use App\Campaign;
use App\Client;
use App\Policies\CampaignPolicy;
use App\Policies\ClientPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ShopPolicy;
use App\Product;
use App\Shop;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Campaign::class     => CampaignPolicy::class,
        Product::class      => ProductPolicy::class,
        Shop::class         => ShopPolicy::class,
        Client::class       => ClientPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerApiAuth();
    }
    
    private function registerApiAuth()
    {
        app('Dingo\Api\Auth\Auth')->extend('basic', function ($app) {
            return new \Dingo\Api\Auth\Provider\Basic($app['auth'], 'email');
        });
    }
}
