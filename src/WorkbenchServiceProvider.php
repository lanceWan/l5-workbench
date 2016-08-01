<?php
namespace Iwanli\Workbench;
use Illuminate\Support\ServiceProvider;
use Iwanli\Workbench\Console\WorkbenchMakeCommand;
use Iwanli\Workbench\Packages\PackageCreator;
class WorkbenchServiceProvider extends ServiceProvider
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
	 * @return void
	 */
	public function boot()
	{
		$path = config_path('workbench.php');

        $this->publishes([
            __DIR__.'/config/config.php' => $path,
        ], 'config');

        if (file_exists($path)) {
            $this->mergeConfigFrom($path, 'workbench');
        }
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

        $this->app->singleton('package.creator', function ($app) {
            return new PackageCreator($app['files'],$app['config']);
        });

        $this->app->singleton('command.workbench', function ($app) {
            return new WorkbenchMakeCommand($app['package.creator']);
        });

        $this->commands('command.workbench');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['package.creator', 'command.workbench'];
	}

}
