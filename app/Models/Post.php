<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class Post extends Model
{
	protected $guarded = [];

	protected $fillable = [
		'id',
		'user_id',
		'post_id',
		'text',
		'attachments',
		'attachments_others',
		'attachments_urls',
		'parent_group_id',
		'post_link',
		'datetime'
	];

	public $timestamps = false;

	public static function search($query, $group_id, $from_group_name) {

		if(empty($from_group_name)){

			$all = static::where('parent_group_id', $group_id)
			->orderBy('post_id', 'desc');

			$requested = static::where('text', 'like', '%'.$query.'%')
			->where('parent_group_id', $group_id)
			->orderBy('post_id', 'desc');
			
			return empty($query) ? $all
			: $requested;

		}
		else{

			$from_group = DB::table('group_lists')
			->where('parent_group_id', $group_id)
			->where('group_name', $from_group_name)
			->first();

			if(is_object($from_group) && $from_group_name != 'Все'){
				return empty($query) ? static::where('post_id', 'like', $from_group->group_id . '%')
				->where('parent_group_id', $group_id)
				->orderBy('post_id', 'desc')
				: static::where('text', 'like', '%'.$query.'%')
				->where('post_id', 'like', $from_group->group_id .'%')
				->where('parent_group_id', $group_id)
				->orderBy('post_id', 'desc');
			}
			else{

				return empty($query) ? static::where('parent_group_id', $group_id)
				->orderBy('post_id', 'desc')
				: static::where('text', 'like', '%'.$query.'%')
				->where('parent_group_id', $group_id)
				->orderBy('post_id', 'desc');

			}
		}
	}

	public static function createPost($parse, $count, $parent_group_id, $user_id) {

		$group_id = $parse['items'][0]['owner_id'];
		$group_id = ltrim($group_id, '-');
		$counter = 0;

		for ($i=0; $i < $count; $i++){

			$item = $parse['items'][$i];
			$attachments = '';
			$attachment_links = '';
			$attachment_others = '';

			$id = $item['id'];
			$post_id = $group_id . $id;
			$post_link = "vk.com/wall-$group_id" . '_' . $id;
			$text = $item['text'];

			if($text == '') {

				$text = 'Нет текста';

			}

			if(isset($item['attachments'])) {

				$attachment_counter = $parse['items'][$i]['attachments'];

				for ($j=0; $j <= count($attachment_counter) - 1; $j++) {
					if(isset($item['attachments'][$j]['type'])){

						$attachment_type = $item['attachments'][$j]['type'];
						if(isset($item['attachments'][$j]["$attachment_type"]['owner_id'])){

							$owner_id = $item['attachments'][$j]["$attachment_type"]['owner_id'];
							$item_id = $item['attachments'][$j]["$attachment_type"]['id'];
							$category = ["photo" => "sizes", "video" => "image"];

							if ($attachment_type == 'video' || $attachment_type == 'photo' || $attachment_type == 'doc') {

								$attachment = "$attachment_type"."$owner_id"."_$item_id";
								$attachments .= $attachment . ',';

								if($attachment_type != 'doc'){

									$pic_links = $item['attachments'][$j]["$attachment_type"]["$category[$attachment_type]"];
									$attachment_link = $pic_links[array_key_last($pic_links)]['url'];
									$attachment_links .= $attachment_link . ',';

								}
								else{

									$pic_links = $item['attachments'][$j]['doc'];
									$attachment_link = $pic_links['url'];
									$attachment_links .= $attachment_link . ',';

								}
							}
							elseif($attachment_type == 'link' || $attachment_type == 'poll'){
							}
							else {

								$attachment_other = "$attachment_type"."$owner_id"."_$item_id";
								$attachment_others .= $attachment_other . ',';

							}
						}
					}
				}
			}

			if(!isset($item['copy_history'])) {

				$post = static::firstOrNew([ 
					'post_id' => $post_id,
					'parent_group_id' => $parent_group_id
				], [
					'parent_group_id' => $parent_group_id,
					'user_id' => $user_id,
					'text' => $text,
					'attachments' => $attachments,
					'attachments_others' => $attachment_others,
					'attachments_urls' => $attachment_links,
					'post_link' => $post_link,
					'datetime' => now()
				]);

				if(!$post->exists){

					$post->save();

					$counter++;

				}

				unset($attachments);
				unset($attachment_others);
				unset($attachment_links);
			}
		}

		return $counter;

	}
}