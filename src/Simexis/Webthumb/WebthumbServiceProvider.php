<?php namespace Simexis\Webthumb;

use Illuminate\Support\ServiceProvider;

class WebthumbServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
            __DIR__.'/../../config/webthumb.php' => config_path('webthumb.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/webthumb.php', 'webthumb'
        );

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['webthumb'] = $this->app->share(function($app)
        {
            return new Webthumb($app['config']);
        });

        $this->app->booting(function()
		{
		  $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		  $loader->alias('Webthumb', 'Simexis\Webthumb\Facades\Webthumb');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
