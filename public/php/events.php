<?php
include_once("settings.php");
session_start();
$user_id = $_SESSION['user_id'];
$group_id = $_POST['group_id'];
$calendar_start = $_POST['start'];
$calendar_end = $_POST['end'];
$Connect = mysqli_connect(HOST, USER, PASSWORD, DB);
$id = $group_id . '%';
$query = "SELECT * FROM `events` WHERE `start` >= '$calendar_start' AND `start` <= '$calendar_end' AND `group_id` = '$group_id' OR (`every` = 'day' AND `post_id` LIKE '$id'  AND `group_id` = '$group_id') OR (`every` = 'week' AND `post_id` LIKE '$id'  AND `group_id` = '$group_id') OR (`every` = 'month' AND `post_id` LIKE '$id'  AND `group_id` = '$group_id') OR (`every` = 'year' AND `post_id` LIKE '$id'  AND `group_id` = '$group_id')";
$result = mysqli_query($Connect, $query);
$res = mysqli_fetch_all($result, MYSQLI_ASSOC);
$data = '[';
foreach ($res as $item) {
	$now = date("Y-m-d H:i:s");
	$item = str_replace("\n","<br>", $item);
	$timelapse = $item['every'];
	$event_start = $item['start'];
	$timezone = $item['timezone'];
	$error = $item['error'];
	$title = $item['title'];
	$title = str_replace('"', '\\"', $title);
	$opacity = 'opacity';
	$editable = false;
	$icon = 'waiting';
	$ad = '';
	if($item['ad'] == 1){
		$ad = 'ad';
	}
	if($timelapse !== 'none'){
		$timelapse_arr = array('year' => 'Y', 'month' => 'M', 'week' => 'W', 'day' => 'D');
		$event_start_hours = date("H", strtotime("$event_start"));
		$event_start_minutes = date("i", strtotime("$event_start"));
		$dow = date("D", strtotime("$event_start"));
		$step = 1;
		$start = new DateTime($event_start);
		$unit = "$timelapse_arr[$timelapse]";
		$start->modify($dow);
		$end = new DateTime($calendar_end);
		$interval = new DateInterval("P{$step}{$unit}");
		$period = new DatePeriod($start, $interval, $end);
		foreach ($period as $date) {
			$new_start = $date->format("Y-m-d");
			$new_start = date("Y-m-d H:i:s", strtotime("$new_start +$event_start_hours hours +$event_start_minutes minutes"));
			if($now < $new_start){
				$opacity = '';
				$editable = 'true';
			}
			if(date("H:i:s", strtotime("$new_start")) == '23:30:00'){
				$end = date("Y-m-d H:i:s", strtotime("$new_start +29 minutes"));
			}
			else{
				$end = $new_start;
			}
			if($calendar_start < $new_start || $calendar_start == $new_start){
				$data .= '{ "id": "' . $item['id'] . '", "post_id": "' . $item['post_id'] . '", "title": "' . $title . '", "classNames": ["' . $ad . '", "' . $opacity . '", "' . $icon . '"], "editable": "' . $editable . '", "start": "' . $new_start . '", "end": "' . $end . '" },';
			}
		}
	}
	else{
		if($now < $event_start){
			$opacity = '';
			$editable = 'true';
		}
		if($error == 'OK'){
			$icon = 'success';
		}
		if($error !== '' && $error !== 'OK'){
			$icon = 'fail';
		}
		if(date("H:i:s", strtotime("$item[start]")) == '23:30:00'){
			$end = date("Y-m-d H:i:s", strtotime("$item[start] +29 minutes"));
		}
		else{
			$end = $item['start'];
		}
		$data .= '{ "id": "' . $item['id'] . '", "post_id": "' . $item['post_id'] . '", "title": "' . $title . '", "classNames": ["' . $ad . '", "' . $opacity . '", "' . $icon . '"], "editable": "' . $editable . '", "start": "' . $item['start'] . '", "end": "' . $end . '" },';
	}
}
$data = mb_substr($data, 0, -1);
$data .= ']';
echo $data;
?>