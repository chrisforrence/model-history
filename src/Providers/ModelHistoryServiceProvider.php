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
        $configPath = __DIR__ . '/../config/model-history.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('model-history.php');
        } else {
            $publishPath = base_path('config/model-history.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/model-history.php';
        $this->mergeConfigFrom($configPath, 'model-history');
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}