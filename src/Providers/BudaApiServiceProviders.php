<?php


namespace Sdtech\LaravelBuda\Providers;


use Illuminate\Support\ServiceProvider;

class BudaApiServiceProviders extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @param
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/budaLaravel.php', 'budaLaravel'
        );
        $this->publishFiles();
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Publish config file for the installer.
     *
     * @return void
     */
    protected function publishFiles()
    {
        $this->publishes([
            __DIR__ . '/../Config/budaLaravel.php' => config_path('budaLaravel.php'),
            ], 'budaLaravel');
    }

}
