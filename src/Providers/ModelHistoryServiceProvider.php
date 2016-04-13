<?php

namespace Cforrence\Providers;

use Illuminate\Support\ServiceProvider;

class ModelHistoryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/model-history.php' => config_path('model-history.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $configPath = __DIR__.'/../../config/model-history.php';
        $this->mergeConfigFrom($configPath, 'model-history');
    }
}
