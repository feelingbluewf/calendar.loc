<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use VkRequest;
use App\Models\User;
use App\Models\Event;
use App\Models\Group;
use App\Models\Post;
use App\Models\Group_list;
use Auth;
use DB;
use Carbon\Carbon;

class VkRequestController extends Controller
{

	public function uploadImage(Request $request) {

		$directory = 'images';
		$original_file_name = request('file')->getClientOriginalName();
		request('file')->storeAs($directory, $original_file_name);

		[$uploadImage, $picture, $error] = VkRequest::uploadImage(Auth::user()->vk_token, $original_file_name, $directory);

		if($uploadImage === true){

			$owner_id = $picture['0']['owner_id'];
			$sizes = $picture[0]['sizes'];
			$url = $sizes[array_key_last($sizes)]['url'] . ',';
			$attachment = 'photo' . $owner_id . '_' . $picture['0']['id'] . ',';

			Storage::delete("$directory" . '/' . "$original_file_name");

			return response()->json(array(
				'url' => $url,
				'attachment' => $attachment,
				'object_id' => $request->id
			));

		}
		else {

			return response()->json(array(
				'error' => $error
			));

		}

	}

	public function post() {

		$now = Carbon::now()->format('Y-m-d H:i:00');

		$events = Event::where('UTC_start', '<=', $now)->where('status', '0')->get();

		foreach($events as $event) {

			[$post, $error] = VkRequest::post($event);

			if($post === true) {

				if($event->every != 'none') {

					$new_start = Carbon::createFromFormat('Y-m-d H:i:s', $event->start)->add(+1, $event->every);

					$new_UTC_start = Carbon::createFromFormat('Y-m-d H:i:s', $event->UTC_start, 'UTC')->add(+1, $event->every);

					Event::duplicateEvent($event, $new_start, $new_UTC_start);
				}

				Event::updateEvent($event->id, [
					'status' => '1',
					'error' => 'OK'
				]);
				
			}
			else {

				Event::updateEvent($event->id, [
					'status' => '1',
					'error' => $error
				]);

			}
		}
	}


	public function getUserGroups() {

		[$getUserGroups, $userGroups, $vkId, $error] = VkRequest::getUserGroups(Auth::user()->vk_token);

		if($getUserGroups === true) {
			$counter = $userGroups['count'];
			for ($i=0; $i < $counter; $i++) {

				$unique_id = Auth::user()->id . $vkId . $userGroups['items'][$i]['id'];

				$group = Group::checkAndCreate($userGroups['items'][$i], $unique_id);

				if($group === true){

					$user_id_arr[] = Auth::user()->id;
					$group_name_arr[] = $userGroups['items'][$i]['name'];
					$group_id_arr[] = $userGroups['items'][$i]['id'];
					$unique_id_arr[] = $unique_id;

				}
			}

			if(!empty($group_id_arr)){

				return response()->json(array(
					'user_id' => $user_id_arr,
					'group_id' => $group_id_arr,
					'group_name' => $group_name_arr,
					'unique_id' => $unique_id_arr
				));

			}

		}
		else {

			return response()->json(array(
				'message' => $error
			), 500);

		}
	}

	public function getSourceGroupAndPostsQuantity(Request $request) {

		// get requested source group and posts quantity
		[$getSourceGroupAndPostsQuantity, $group, $sourceGroupPostsQuantity, $error] = VkRequest::getSourceGroupAndPostsQuantity($request->group_name, Auth::user()->vk_token);

		// if successfully
		if($getSourceGroupAndPostsQuantity === true) {

			// source group VK id
			$sourceGroupId = $group[0]['id'];
		}
		else {
			return response()->json(array(
				'message' => $error
			), 500);
		}

		$offset = 0; //offset

		//check if this source group already exists in db
		$existingSourceGroup = Group_list::getSourceGroupByParent($request->parent_group_id, $sourceGroupId);

		//if exists
		if($existingSourceGroup) {
			//offset = post quantity
			$offset = $existingSourceGroup->quantity; //post quantity

		}

		// if requested source group posts quantity bigger than written quantity by user
		if($sourceGroupPostsQuantity > $request->post_quantity) {

			$postsQuantity = $request->post_quantity;

		}
		else {

			$postsQuantity = $sourceGroupPostsQuantity;

		}

		// if offset + requested quantity of posts bigger than total quantity of source group posts
		if($offset + $postsQuantity > $sourceGroupPostsQuantity) {

			$postsQuantity = abs($postsQuantity - $offset);

		}


		return response()->json(array(
			'offset' => $offset, 
			'postsQuantity' => $postsQuantity,
			'sourceGroup' => $group
		));

	}

	public function parsePosts(Request $request) {

		//get posts
		[$getSource, $parse, $count, $error] = VkRequest::getPosts($request->sourceGroup[0]['id'], Auth::user()->vk_token, $request->offset);

		//if posts recieved
		if($getSource === true){

			//post count = created posts
			$post_count = Post::createPost($parse, $count, $request->parent_group_id, Auth::user()->id);

			//update group list
			$parent_group = Group_list::updateOrCreate($request->sourceGroup, $request, $post_count);

		}
		else {

			return response()->json(array(
				'message' => $error
			), 500);

		}

		return response()->json(array(
			'group_name' => $parent_group->group_name, 
			'group_id' => $parent_group->group_id,
			'quantity' => $parent_group->quantity,
			'status' => $parent_group->status,
			'group_screen_name' => $parent_group->group_screen_name,
			'unique_id' => $parent_group->unique_id,
			'itterationNum' => $request->itterationNum
		));

	}


	public function sourceParse() {

		$groups = Group_list::where('status', '1')->orderBy('timestamp', 'ASC')->get();

		foreach ($groups as $group) {

			[$parse, $count, $error_msg] = VkRequest::sourceParse($group->user->vk_token, $group->group_id);

			if($error_msg == 'OK') {

				$post_count = Post::createPost($parse, $count, $group->parent_group_id, $group->user->id);

				$group->increment('quantity', $post_count, [
					'error' => $error_msg,
					'status' => 1,
					'timestamp' => now()
				]);

			}
			else {

				$group->update([
					'error' => $error_msg,
					'status' => 0
				]);

			}
		}
	}
}
