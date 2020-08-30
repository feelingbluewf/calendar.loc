<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class VkServiceProvider extends ServiceProvider {

	public function register() {

		$this->app->bind('VkRequest', 'App\Services\VkRequest');

	}

}