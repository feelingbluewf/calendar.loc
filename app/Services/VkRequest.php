<?php 

namespace App\Services;

use VK\Client\VKApiClient;
use VK\Client\VKApiRequest;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuthResponseType;
use App\Exceptions\VkException;
use Carbon\Carbon;

class VkRequest {

	public static $vk;

	public function __construct() {

		self::$vk = new VKApiClient();

	}

	public function uploadImage($token, $original_file_name, $directory) {

		try{

			$token = $token;

			$address = self::$vk->photos()->getWallUploadServer($token);

			$path = $_SERVER['DOCUMENT_ROOT'] . '/../storage/app/' . "$directory" . '/' . "$original_file_name";

			$photo = self::$vk->getRequest()->upload($address['upload_url'], 'photo', $path);
			$result = self::$vk->photos()->saveWallPhoto($token, array(
				'server' => $photo['server'],
				'photo' => $photo['photo'],
				'hash' => $photo['hash'],
			));

			return [true, $result, ''];

		}
		catch(\Exception $e) {

			return [false, '', $e->getMessage()];

		}

	}


	public function post($event) {

		try{

			$text = $event->text;

			if($event->link == '1' && !empty($event->post_link)) {

				if($text == 'Нет текста'){

					$text = "Источник: $event->post_link";

				} 
				else{

					$text .= "\n\n Источник: $event->post_link";

				}
			} 
			else {

				if($text == 'Нет текста') {

					$text = '';

				}
			}

			$all_attachments = $event->attachments . $event->attachments_others;

			$post = self::$vk->wall()->post($event->user->vk_token, array(
				'owner_id' => '-' . $event->group_id,
				'message' => $text,
				'attachments' => $all_attachments,
				'mark_as_ads' => $event->ad,
				'signed' => $event->sign,
				'close_comments' => $event->disable_comments,
				'v' => '5.103'
			));

			return [true, 'OK'];

		}
		catch(\Exception $e) {

			return [false, $e->getMessage()];

		}

	}

	public function getUserGroups($token) {

		try {

			$user = self::$vk->users()->get($token, array());

			$user_id_vk = $user[0]['id'];

			$admin_groups = self::$vk->groups()->get($token, array(
				'user_id' => $user_id_vk,
				'extended' => '1',
				'filter' => 'moder',
				'count' => '1000',
				'v' => '5.103'
			));

			return [true, $admin_groups, $user_id_vk, ''];

		}
		catch(\Exception $e) {

			return [false, '', '', $e->getMessage()];

		}
		
	}

	public function getSourceGroupAndPostsQuantity($group_name, $token) {

		try{

			$group = self::$vk->groups()->getByid($token, array(
				'group_id' => $group_name,
				'v' => '5.103'
			));

			$parse = self::$vk->wall()->get($token, array(
				'owner_id' => '-' . $group[0]['id'], 
				'count' => '1',
				'offset' => '0',
				'filter' => 'all',
				'v' => '5.103',
			));

			return [true, $group, $parse['count'], ''];
		}
		catch(\Exception $e) {

			return [false, '', '', $e->getMessage()];

		}

	}

	public function getPosts($group_id, $token, $offset = 0, $count = 100) {

		try{

			// usleep(250000);

			$parse = self::$vk->wall()->get($token, array(
				'owner_id' => '-' . $group_id, 
				'count' => $count,
				'offset' => $offset,
				'filter' => 'all',
				'v' => '5.103',
			));

			return [true, $parse, count($parse['items']), ''];
		}
		catch(\Exception $e) {

			return [false, '', '', $e->getMessage()];

		}
	}

	public function sourceParse($token, $group_id) {

		try{

			$counter = 20;

			$parse = self::$vk->wall()->get($token, array(
				'owner_id' => '-' . $group_id, 
				'count' => $counter,
				'filter' => 'all',
				'v' => '5.103',
			));

			$count = count($parse['items']);

			return [$parse, $count, 'OK'];

		}
		catch (\Exception $e) {

			return ['', '', $e->getMessage()];
		}

	}

	public function checkToken($token) {

		try {

			$group = self::$vk->groups()->getByid($token, array(
				'group_id' => 'bearfoot',
				'v' => '5.103'
			));

			return [true, ''];

		}
		catch(\Exception $e) {

			return [false, $e->getMessage()];

		}
	}
}

