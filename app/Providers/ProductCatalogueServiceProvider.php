<?php

namespace App\Providers;

use App\Services\Access\Access;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class ProductCatalogueServiceProvider
 * @package App\Providers
 */
class ProductCatalogueServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Package boot method
     */
    public function boot()
    {
        
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
    }

    /**
     * Register service provider bindings
     */
    public function registerBindings()
    {

        $this->app->bind(
            \App\Repositories\Backend\ProductCatalogue\ProductCatalogueRepositoryContract::class,
            \App\Repositories\Backend\ProductCatalogue\EloquentProductCatalogueRepository::class
        );

    }
    
}