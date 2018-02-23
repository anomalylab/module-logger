<?php

namespace Anomaly\Module\Logger;

use Anomaly\Addon\AddonServiceProvider;
use Anomaly\Routing\Router;

/**
 *	Class LoggerServiceProvider
 *
 *	@link			https://anomaly.ink
 *	@author			Anomaly lab, Inc <support@anomaly.ink>
 *	@author			Bill Li <bill.li@anomaly.ink>
 *	@package		Anomaly\Module\Logger\LoggerServiceProvider
 */
class LoggerServiceProvider extends AddonServiceProvider
{
	/**
	 *	Additional addon plugins.
	 *
	 *	@var		array|null
	 */
	protected $plugins = [];

	/**
	 *	The addon Artisan commands.
	 *
	 *	@var		array|null
	 */
	protected $commands = [];

	/**
	 *	The addon's scheduled commands.
	 *
	 *	@var		array|null
	 */
	protected $schedules = [];

	/**
	 *	The addon routes.
	 *
	 *	@var		array|null
	 */
	protected $routes = [
		'logger'			=> 'Admin\LoggerController@index',
 ];

	/**
	 *	The addon middleware.
	 *
	 *	@var		array|null
	 */
	protected $middleware = [
		//Anomaly\Module\Logger\Http\Middleware\ExampleMiddleware::class
	];

	/**
	 *	The addon route middleware.
	 *
	 *	@var		array|null
	 */
	protected $routeMiddleware = [];

	/**
	 *	The addon event listeners.
	 *
	 *	@var		array|null
	 */
	protected $listeners = [
		//Anomaly\Module\Logger\Event\ExampleEvent::class => [
		//	Anomaly\Module\Logger\Listener\ExampleListener::class,
		//],
	];

	/**
	 *	The addon alias bindings.
	 *
	 *	@var		array|null
	 */
	protected $aliases = [
		//'Example' => Anomaly\Module\Logger\Example::class
	];

	/**
	 *	The addon class bindings.
	 *
	 *	@var		array|null
	 */
	protected $bindings = [
        //LoggerLoggerEntryModel::class => LoggerModel::class,
    ];

	/**
	 *	The addon singleton bindings.
	 *
	 *	@var		array|null
	 */
	protected $singletons = [
        //LoggerRepositoryInterface::class => LoggerRepository::class,
    ];

	/**
	 *	Additional service providers.
	 *
	 *	@var		array|null
	 */
	protected $providers = [
		//	\ExamplePackage\Provider\ExampleProvider::class
	];

	/**
	 *	The addon view overrides.
	 *
	 *	@var		array|null
	 */
	protected $overrides = [
		//	'errors/404' => 'module::errors/404',
		//	'errors/500' => 'module::errors/500',
	];

	/**
	 *	Register the addon.
	 */
	public function register() : void
	{
		//	Run extra pre-boot registration logic here.
		//	Use method injection or commands to bring in services.
	}

	/**
	 *	Boot the addon.
	 */
	public function boot() : void
	{
		//	Run extra post-boot registration logic here.
		//	Use method injection or commands to bring in services.
	}

	/**
	 *	Map additional addon routes.
	 *
	 *	@param		Router		$router
	 */
	public function map(Router $router) : void
	{
		//	Register dynamic routes here for example.
		//	Use method injection or commands to bring in services.
	}

}
