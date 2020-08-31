<?php

namespace App\Services;
use Event;

class StringService {

	// Красиво обрезаем текст и если надо в конце ставим ...
	public function strSize($str, $length) {

		$str = strip_tags($str);

		if (strlen($str) >= $length) {

			//UTF-8
			//$str = iconv("utf8mb4_unicode_ci","windows-1251", $str);
			$str = iconv("UTF-8", "UTF-8", $str);
			$str = substr($str, 0, $length);
			//$str = iconv("windows-1251", "utf8mb4_unicode_ci", $str);
			$str = substr($str, 0, strrpos($str, ' '));
			$str = rtrim($str, "!,.-");
			$str = substr($str, 0, strrpos($str, ' '));
			$str = rtrim($str, "!,.-");
			$str = trim($str);

			$str .= "...";

		}

		return $str;

	}

	public function makeEventArr($user_id, $postInfo, $request, $UTC_start) {

		$link = \Session::get('link') ? \Session::get('link') : '0';
		$ad = \Session::get('ad') ? \Session::get('ad') : '0';
		$sign = \Session::get('sign') ? \Session::get('sign') : '0';
		$disable_comments = \Session::get('disable_comments') ? \Session::get('disable_comments') : '0';
		$every = \Session::get('every') ? \Session::get('every') : 'none';
		


		if(!empty($postInfo)) {

			$title = $this->strSize($postInfo->text, 180);

			$event = [

				'user_id' => $user_id,
				'post_id' => $postInfo->post_id,
				'group_id' => $request->group_id,
				'title' => $title,
				'text' => $postInfo->text,
				'attachments' => $postInfo->attachments,
				'attachments_others' => $postInfo->attachments_others,
				'attachments_urls' => $postInfo->attachments_urls,
				'link' => $link,
				'ad' => $ad,
				'sign' => $sign,
				'disable_comments' => $disable_comments,
				'every' => $every,
				'post_link' => $postInfo->post_link,
				'start' => $request->start,
				'UTC_start' => $UTC_start


			];

		}
		else {

			if(empty($request->text)) {

				$request->text = 'Нет текста';

			}

			$title = $this->strSize($request->text, 180);

			$post_id = $user_id . $request->group_id;

			[$urls, $attachments] = $this->makeAttachmentsStr($request->attachments);

			$event = [

				'user_id' => $user_id,
				'post_id' => $post_id,
				'group_id' => $request->group_id,
				'title' => $title,
				'text' => $request->text,
				'attachments' => $attachments,
				'attachments_others' => '',
				'attachments_urls' => $urls,
				'link' => $request->link,
				'ad' => $request->ad,
				'sign' => $request->sign,
				'disable_comments' => $request->disable_comments,
				'every' => $request->every,
				'post_link' => '',
				'start' => $request->start,
				'UTC_start' => $UTC_start

			];

		}

		return $event;

	}

	public function makeAttachmentsStr($allAttachments) {

		$urls = '';
		$attachments = '';

		if(!empty($allAttachments)) {
			foreach($allAttachments as $attachment) {

				$urls .= $attachment['url'];
				$attachments .= $attachment['attachment'];

			}
		}

		return [$urls, $attachments];

	}

	public function getEventsJSON($events, $calendar_start, $calendar_end) {

		$data = '[';
		foreach ($events as $event) {
			$now = date("Y-m-d H:i:s");
			$every = $event['every'];
			$event_start = $event['start'];
			$timezone = $event['timezone'];
			$error = $event['error'];
			$title = str_replace(array("\r\n", "\r", "\n"), '', $event['title']);
			$title = str_replace('"', '\"', $title);
			$opacity = 'opacity';
			$editable = false;
			$icon = 'waiting';
			$ad = '';
			$description = '';
			if($event['ad'] == 1){
				$ad = 'ad';
			}
			if($every != 'none'){
				$every_arr = array('year' => 'Y', 'month' => 'M', 'week' => 'W', 'day' => 'D');
				$event_start_hours = date("H", strtotime("$event_start"));
				$event_start_minutes = date("i", strtotime("$event_start"));
				$dow = date("D", strtotime("$event_start"));
				$step = 1;
				$start = new \DateTime($event_start);
				$unit = "$every_arr[$every]";
				$start->modify($dow);
				$end = new \DateTime($calendar_end);
				$interval = new \DateInterval("P{$step}{$unit}");
				$period = new \DatePeriod($start, $interval, $end);
				foreach ($period as $date) {
					$new_start = $date->format("Y-m-d");
					$new_start = date("Y-m-d H:i:s", strtotime("$new_start +$event_start_hours hours +$event_start_minutes minutes"));
					if($now < $new_start){
						$opacity = '';
						$editable = true;
					}
					if($error == 'OK' && $event['status'] == '1'){
						$opacity = 'opacity';
						$editable = false;
						$icon = 'success';
					}
					if($error !== '' && $error !== 'OK'){
						$opacity = 'opacity';
						$editable = false;
						$icon = 'fail';
						$description = $error;
					}
					if(date("H:i:s", strtotime("$new_start")) == '23:30:00'){
						$end = date("Y-m-d H:i:s", strtotime("$new_start +29 minutes"));
					}
					else{
						$end = $new_start;
					}
					if($calendar_start < $new_start || $calendar_start == $new_start){
						$data .= '{ "id": "' . $event['id'] . '", "post_id": "' . $event['post_id'] . '", "title": "' . $title . '", "classNames": ["' . $ad . '", "' . $opacity . '", "' . $icon . '"], "editable": "' . $editable . '", "start": "' . $new_start . '", "end": "' . $end . '" },';
					}
				}
			}
			else{
				if($now < $event_start){
					$opacity = '';
					$editable = true;
				}
				if($error == 'OK' && $event['status'] == '1'){
					$opacity = 'opacity';
					$editable = false;
					$icon = 'success';
				}
				if($error != '' && $error != 'OK'){
					$opacity = 'opacity';
					$editable = false;
					$icon = 'fail';
					$description = $error;
				}
				if(date("H:i:s", strtotime("$event[start]")) == '23:30:00'){
					$end = date("Y-m-d H:i:s", strtotime("$event[start] +29 minutes"));
				}
				else{
					$end = $event['start'];
				}
				$data .= '{ "id": "' . $event['id'] . '", "post_id": "' . $event['post_id'] . '", "title": "' . $title . '", "classNames": ["' . $ad . '", "' . $opacity . '", "' . $icon . '"], "editable": "' . $editable . '", "start": "' . $event['start'] . '", "end": "' . $end . '", "description": "' . $description . '" },';
			}
		}

		$data = mb_substr($data, 0, -1);

		$data .= ']';

		return $data;

	}
}

?>
