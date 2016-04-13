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
    protected $defer = true;
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/model-history.php' => config_path('model-history.php')
        ], 'config');
        
        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../../config/model-history.php';
        $this->mergeConfigFrom($configPath, 'model-history');
    }
}