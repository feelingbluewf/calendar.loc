<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Auth;

class ControlPanelController extends Controller
{
    public function index()
    {

      return view('calendar.control_panel', [
            'groups' => group::index(),
        ]);
      
    }

   public function hideGroup(Request $request) {

   	group::where('user_id', Auth::user()->id)
   	->where('unique_id', $request->unique_id)
   	->update(['hide' => $request->hide]);

   }
}
