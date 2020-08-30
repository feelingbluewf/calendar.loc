<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Event extends Model
{
    protected $guarded = [];

    protected $fillable = [
    	'user_id',
    	'id',
    	'post_id',
    	'group_id',
    	'title',
    	'text',
    	'attachments',
    	'attachments_others',
    	'attachments_urls',
    	'post_link',
        'link',
    	'ad',
    	'sign',
    	'disable_comments',
        'UTC_start',
        'start',
        'every',
        'status',
        'error'
    ];

    public $timestamps = false;

    public static function index($calendar_start, $calendar_end, $group_id) {

        return static::where(function ($query) use($calendar_start, $calendar_end, $group_id){
            $query->where('start', '>=', $calendar_start)
            ->where('start', '<=', $calendar_end)
            ->where('group_id', $group_id);
        })->orWhere(function($query) use ($group_id) {
            $query->where('every', '!=', 'none')
            ->where('group_id', $group_id);
        })
        ->get();

    }


    public static function viewEvent($id) {

        return static::where('id', $id)
        ->first();
    }

    public static function createEvent($event) {

        static::create([
            'user_id' => $event['user_id'],
            'post_id' => $event['post_id'],
            'group_id' => $event['group_id'],
            'title' => $event['title'],
            'text' => $event['text'],
            'attachments' => $event['attachments'],
            'attachments_others' => $event['attachments_others'],
            'attachments_urls' => $event['attachments_urls'],
            'link' => $event['link'],
            'ad' => $event['ad'],
            'sign' => $event['sign'],
            'disable_comments' => $event['disable_comments'],
            'every' => $event['every'],
            'post_link' => $event['post_link'],
            'start' => $event['start'],
            'UTC_start' => $event['UTC_start']
        ]);
    }

    public static function duplicateEvent($event, $new_start, $new_UTC_start) {

        static::create([
            'user_id' => $event['user_id'],
            'post_id' => $event['post_id'],
            'group_id' => $event['group_id'],
            'title' => $event['title'],
            'text' => $event['text'],
            'attachments' => $event['attachments'],
            'attachments_others' => $event['attachments_others'],
            'attachments_urls' => $event['attachments_urls'],
            'link' => $event['link'],
            'ad' => $event['ad'],
            'sign' => $event['sign'],
            'disable_comments' => $event['disable_comments'],
            'every' => $event['every'],
            'post_link' => $event['post_link'],
            'start' => $new_start,
            'UTC_start' => $new_UTC_start
        ]);
    }

    public static function updateEvent($id, $arr) {

        static::where('id', $id)
        ->update($arr);

    }

    public function user() {

        return $this->belongsTo('App\Models\User');

    }

}