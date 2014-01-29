<?php namespace Shopavel\Themes;

use Illuminate\Support\ServiceProvider;

class ThemesServiceProvider extends ServiceProvider {

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
		$this->package('shopavel/themes', 'shopavel/themes', __DIR__.'/../../');

		include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerThemeManager();

		$this->registerViewFinder();
	}

	public function registerThemeManager()
	{
		$this->app['themes'] = $this->app->share(function($app)
		{
			$manager = new ThemeManager($app['config']->get('shopavel/themes::theme'), $app['view']);

			$manager->registerAssets();

			$directories = $app['files']->directories(app_path() . '/../themes');

			foreach ($directories as $directory)
			{
				$app['view']->addNamespace(basename($directory), $directory . '/views/');
			}

			return $manager;
		});
	}

	/**
	 * Register the view finder implementation.
	 *
	 * @return void
	 */
	public function registerViewFinder()
	{
		$this->app['view.finder'] = $this->app->share(function($app)
		{
			$paths = $app['config']['view.paths'];

			$finder = new FileViewFinder($app['files'], $paths);

			$finder->setNamespace($app['config']->get('shopavel/themes::theme'));

			return $finder;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('themes');
	}

}