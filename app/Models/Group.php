<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Group extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public static function index()
    {

        return static::all()->where('user_id', Auth::user()->id);

    }

    public static function checkGroup($group_id) {

        $group = static::where('user_id', Auth::user()->id)
        ->where('group_id', $group_id)
        ->first();

        if(empty($group)) {

            return false;

        }

        return $group;

    }

    public static function checkAndCreate($userGroups, $unique_id) {

        $group = static::firstOrNew([ 
            'unique_id' => $unique_id
        ], [
            'user_id' => Auth::user()->id,
            'avatar' => $userGroups['photo_50'],
            'group_name' => $userGroups['name'],
            'group_id' => $userGroups['id'],
            'hide' => '0'
        ]);

        if(!$group->exists){

            $group->save();

            return true;

        }

    }

    public function groupList() {

        return $this->hasMany('App\Models\Group_list', 'parent_group_id', 'group_id');

    }


    public static function select($query) {

        $group = static::where('user_id', Auth::user()->id);

        if(!empty($group->where('hide', 0)->first())) {

            $session_group_id = session()->get('group_id', '');

            if(empty($query) && !empty($session_group_id) && !empty(end($session_group_id))){

                $group_id_start = $group
                ->where('hide', 0)
                ->where('group_id', end($session_group_id))
                ->first();

            }
            else {
                $group_id_start = $group
                ->where('hide', 0)
                ->first();
            }

            $group_id_query = $group
            ->where('hide', 0)
            ->where('group_id', $query)
            ->first();

            return empty($query) ? $group_id_start->group_id
            : $group_id_query->group_id;

        }
    }

}
