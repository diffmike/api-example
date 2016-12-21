<?php
namespace App\Providers;

use AdminSection;
use AdminTemplate;
use PackageManager;
use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $sections = [
        \App\User::class              => \App\Admin\Users::class,
        \App\Company::class           => \App\Admin\Companies::class,
        \App\Shop::class              => \App\Admin\Shops::class,
        \App\Client::class            => \App\Admin\Clients::class,
        \App\Product::class           => \App\Admin\Products::class,
        \App\Campaign::class          => \App\Admin\Campaigns::class,
        \App\Check::class             => \App\Admin\Checks::class
    ];
    
    /**
     * Register sections.
     *
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
        parent::boot($admin);
        
        $this->registerRoutes();
        $this->registerViewComposers();
        $this->registerMediaPackages();
    }
    
    private function registerRoutes()
    {
        $this->app['router']->group([
            'prefix'     => config('sleeping_owl.url_prefix'),
            'middleware' => config('sleeping_owl.middleware')
        ], function ($router) {
            $router->get('', ['as' => 'admin.dashboard', function () {
                    $content = '';
                    return AdminSection::view($content, 'Панель управления');
                }
            ]);
        });
    }
    
    private function registerMediaPackages()
    {
        PackageManager::add('front.controllers')->js(null, asset('js/controllers.js'));
    }
    
    private function registerViewComposers()
    {
        view()->composer(AdminTemplate::getViewPath('_partials.header'), function($view) {
            $view->getFactory()->inject(
                'navbar.right', view('auth.partials.navbar')
            );
        });
    }
}
