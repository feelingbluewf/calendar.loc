<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Post;
use Auth;
use Illuminate\Support\Facades\DB;
use StringService;
use Carbon\Carbon;

class CalendarPostController extends Controller
{

  public function createEvent(Request $request) {

    if(!empty($request)) {

      $date = Carbon::createFromFormat('Y-m-d H:i:s', $request->start, $request->timezone);

      $postInfo = Post::where('post_id', $request->id)->first();

      if(!empty($postInfo)) {

        $event = StringService::makeEventArr(Auth::user()->id, $postInfo, $request, $date->setTimezone('UTC')->format('Y-m-d H:i:s'));

        Event::createEvent($event);

      }
      else {

        $event = StringService::makeEventArr(Auth::user()->id, '', $request, $date->setTimezone('UTC')->format('Y-m-d H:i:s'));

        Event::createEvent($event);

      }
    }
    else {
      abort('404');
    }
  }

  public function updateEvent(Request $request) {

    $date = Carbon::createFromFormat('Y-m-d H:i:s', $request->start, $request->timezone);

    Event::updateEvent($request->id, [
      'start' => $request->start,
      'UTC_start' => $date->setTimezone('UTC')->format('Y-m-d H:i:s')
    ]);

  }

  public function updatePostData(Request $request) {

    \Session::put('link', $request->link);
    \Session::put('ad', $request->ad);
    \Session::put('sign', $request->sign);
    \Session::put('disable_comments', $request->disable_comments);
    \Session::put('every', $request->every);

    if(empty($request->text)) {

      $request->text = 'Нет текста';

    }

    $title = StringService::strSize($request->text, 180);

    [$urls, $attachments] = StringService::makeAttachmentsStr($request->attachments);

    $date = Carbon::createFromFormat('Y-m-d H:i:s', $request->start, $request->timezone);

    Event::updateEvent($request->object_id, [
      'text' => $request->text,
      'title' => $title,
      'attachments' => $attachments,
      'attachments_urls' => $urls,
      'link' => $request->link,
      'ad' => $request->ad,
      'sign' => $request->sign,
      'disable_comments' => $request->disable_comments,
      'every' => $request->every,
      'start' => $request->start,
      'UTC_start' => $date->setTimezone('UTC')->format('Y-m-d H:i:s')
    ]);

  }

  public function deleteEvent(Request $request) {

    Event::where('id', $request->object_id)
    ->delete();

  }

  public function viewPostData(Request $request) {

    $eventData = Event::viewEvent($request->object_id);

    return response()->json(array(
      'attachments_urls' => $eventData->attachments_urls,
      'attachments' => $eventData->attachments,
      'attachments_others' => $eventData->attachments_others,
      'text' => $eventData->text,
      'id' => $eventData->id,
      'link' => $eventData->link,
      'ad' => $eventData->ad,
      'sign' => $eventData->sign,
      'disable_comments' => $eventData->disable_comments,
      'every' => $eventData->every,
      'start' => $eventData->start,
      'error' => $eventData->error
    ), 200);

  }

  public function deletePostAttachment(Request $request) {

    Event::updateEvent($request->object_id, [
      "attachments_urls" => DB::raw("REPLACE(attachments_urls, '$request->url', '')"),
      "attachments" => DB::raw("REPLACE(attachments, '$request->attachment', '')")
    ]);

    $event = Event::viewEvent($request->object_id);

    return response()->json(array(
      'attachments' => $event->attachments,
      'attachments_others' => $event->attachments_others,
    ), 200);

  }

  public function getEvents(Request $request) {

    $events = Event::index($request->start, $request->end, $request->group_id);

    $eventsData = StringService::getEventsJSON($events, $request->start, $request->end);

    return $eventsData;

  }
}

