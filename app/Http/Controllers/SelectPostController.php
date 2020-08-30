<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Auth;

class SelectPostController extends Controller
{
    public function viewPostData(Request $request) {

    	$post = Post::where('post_id', $request->post_id)
        ->first();

    	$text = $post->text;
    	$text = str_replace(array("\r", "\n"), '', $text);
        
    	return response()->json(array(
    	'fulltext' => $text,
    	'pictures' => $post->attachments_urls,
    	'attachments' => $post->attachments_urls,
    	'attachments_others' => $post->attachments_others,
    	'post_link' => $post->post_link
    	), 200);

    }
}
