<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group_list;
use App\Models\Group;
use App\Models\Post;
use Auth;
use Illuminate\Support\Facades\DB;

class GroupPanelController extends Controller
{

    public function index($group_id)
    {

        $checkGroup = Group::checkGroup($group_id);

        if($checkGroup !== false) {

          return view('calendar.group-panel', [
            'group_id' => $group_id,
            'group_name' => $checkGroup->group_name
        ]);

      }
      else {

        return abort('403');
        
      }
  }

  public function startSourceParse(Request $request) {

    Group_list::where('user_id', Auth::user()->id)
    ->where('parent_group_id', $request->parent_group_id)
    ->where('group_id', $request->group_id)
    ->update(['status' => '1']);

}

public function stopSourceParse(Request $request) {

 $group = Group_list::where('user_id', Auth::user()->id)
 ->where('parent_group_id', $request->parent_group_id)
 ->where('group_id', $request->group_id)->first();

 $group->update(['status' => '0']);

 return response()->json(array(
    'error' => $group->error
));

}

public function deleteSource(Request $request) {

    Post::where('post_id', 'like', $request->group_id . '%')
    ->where('parent_group_id', $request->parent_group_id)
    ->delete();

    Group_list::where('parent_group_id', $request->parent_group_id)
    ->where('group_id', $request->group_id)
    ->delete();

}
}
