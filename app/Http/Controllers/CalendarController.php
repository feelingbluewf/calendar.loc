<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class CalendarController extends Controller
{
	public function index()
	{
		return view('calendar.calendar');
	}

	public function redirect() {
		if (Auth::check()){
			return redirect('/calendar');
		}
		else{
			return redirect('/login');
		}
	}
}
