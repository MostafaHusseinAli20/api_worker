<?php

namespace App\Http\Controllers\AdminDashbord;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostStatusRequest;
use App\Models\Post;
use App\Notifications\AdminPost;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PostStatusController extends Controller
{
    public function changeStatus(PostStatusRequest $request)
    {
            $post = Post::find($request->post_id);
            $post->update([
                'status' => $request->status,
                'rejected_reason' => $request->rejected_reason
            ]);
            
            Notification::send($post->worker, new AdminPost($post->worker , $post));

            return response()->json([
                'message' => 'Post has been updated successfully'
            ]);
    }
}
