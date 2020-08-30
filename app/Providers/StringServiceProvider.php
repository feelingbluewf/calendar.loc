<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StringServiceProvider extends ServiceProvider {

	public function register() {

		$this->app->bind('StringService', 'App\Services\StringService');

	}

}