<?php namespace Simexis\Webthumb;

use Illuminate\Support\ServiceProvider;

class WebthumbServiceProvider extends ServiceProvider {

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
		$this->app->singleton('webthumb', function($app)
        {
            return new Webthumb($app['config']);
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'webthumb'
		];
	}

}
