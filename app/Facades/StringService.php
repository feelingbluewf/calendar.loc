<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class StringService extends Facade {

	protected static function getFacadeAccessor() {

		return 'StringService';

	}

}