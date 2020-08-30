<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class VkService extends Facade {

	protected static function getFacadeAccessor() {

		return 'VkRequest';

	}

}