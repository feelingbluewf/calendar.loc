<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Group_list extends Model
{
  protected $guarded = [];

  protected $fillable = [
    'id',
    'user_id',
    'avatar',
    'parent_group_name',
    'parent_group_id',
    'group_name',
    'group_id',
    'group_screen_name',
    'unique_id',
    'status',
    'quantity',
    'anti-repeat',
    'error',
    'timestamp'
  ];

  public $timestamps = false;

  public static function index()
  {

    return static::all()
    ->where('user_id', Auth::user()->id);

  }

  public static function getGroupByParent($parent_group_id)
  {
    return static::all()
    ->where('parent_group_id', $parent_group_id);
  }


  public static function updateOrCreate($group, $request, $post_count) {

    $unique_id = Auth::user()->id . $request->parent_group_id . $group[0]['id'];

    $parent_group = static::firstOrNew([ 
      'unique_id' => $unique_id

    ], [
      'user_id' => Auth::user()->id,
      'avatar' => $group[0]['photo_50'],
      'parent_group_name' => $request->parent_group_name,
      'parent_group_id' => $request->parent_group_id,
      'group_name' => $group[0]['name'],
      'group_id' => $group[0]['id'],
      'group_screen_name' => $group[0]['screen_name'],
      'status' => '1',
      'quantity' => $post_count,
      'error' => 'OK'
    ]);

    if(!$parent_group->exists){

      $parent_group->save();

        // return [true, $group];

    }
    else{

      $parent_group->increment('quantity', $post_count);

        // return [false, $group];

    }

    return $parent_group;

  }

  public static function getSourceGroupByParent($parent_group_id, $source_group_id) {

    return static::where('parent_group_id', $parent_group_id)
    ->where('group_id', $source_group_id)->first();

  }

  public function group() {

    return $this->belongsTo('App\Models\Group');

  }

  public function user() {

    return $this->belongsTo('App\Models\User');

  }


}
