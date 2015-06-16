<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use App;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
//			'Caffeinated\Menus\MenusServiceProvider'
		);

//App::register('App\Providers\MenuServiceProvider');
//App::register('App\Providers\GeneralMenuProvider');

// AliasLoader::getInstance()->alias(
// 	'Menus', 'Caffeinated\Menus\Facades\Menu'
// );


	}

}
