<?php

namespace App\Providers;

use App\Api\Transformers\ShopTransformer;
use App\Api\Transformers\UserTransformer;
use App\Client;
use App\Contracts\CheckImporterContract;
use App\Contracts\ShopImporterContract;
use App\Observers\ClientObserver;
use App\Services\XmlCheckImporter;
use App\Services\XmlShopImporter;
use App\Shop;
use App\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Dingo\Api\Transformer\Factory as TransformerFactory;
use Log;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RavenHandler;
use Raven_Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param TransformerFactory $transformerFactory
     */
    public function boot(TransformerFactory $transformerFactory)
    {
        $this->bootTransformers($transformerFactory);
        $this->bootMorphMap();
        $this->bootObservers();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->registerBindings();
        $this->registerSentryAsLogger();
    }
    
    private function bootMorphMap()
    {
        Relation::morphMap(['users' => User::class]);
    }
    
    /**
     * @param TransformerFactory $factory
     *
     * @internal param TransformerFactory $transformerFactory
     */
    private function bootTransformers(TransformerFactory $factory)
    {
        $factory->register(Shop::class, ShopTransformer::class);
        $factory->register(User::class, UserTransformer::class);
    }
    
    private function registerSentryAsLogger()
    {
        $client = new Raven_Client(config('sentry.dsn'));
        $handler = new RavenHandler($client);
        $handler->setFormatter(new LineFormatter("%message% %context% %extra%\n"));
        
        Log::getMonolog()->pushHandler($handler);
    }
    
    private function bootObservers()
    {
        Client::observe(ClientObserver::class);
    }
    
    private function registerBindings()
    {
        $this->app->bind(ShopImporterContract::class, XmlShopImporter::class);
        $this->app->bind(CheckImporterContract::class, XmlCheckImporter::class);
    }
}
