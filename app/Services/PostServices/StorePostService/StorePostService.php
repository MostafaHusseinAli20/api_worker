<?php

namespace App\Services\PostServices\StorePostService;

use App\Models\Admin;
use App\Models\Post;
use App\Models\PostPhoto;
use App\Notifications\AdminPost;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StorePostService 
{
    protected $model;
    public function __construct()
    {
        $this->model = new Post();
    }

    public function adminPercent($price)
    {
        $discount = $price * 0.05;
        $priceAfterDiscount = $price - $discount;
        return $priceAfterDiscount;
    }

    public function storePost($data)
    {
        $data = $data->except('photos');
        $data['price'] = $this->adminPercent($data['price']);
        $data['worker_id'] = auth()->guard('worker')->id();
        $post = Post::create($data);
        return $post;
    }


    public function storePostPhotos($request, $postId)
    {
        foreach ($request->file('photos') as $photo) {
            $postPhotos = new PostPhoto();
            $postPhotos->post_id = $postId;
            $postPhotos->photo = $photo->store('posts');
            $postPhotos->save();
        }
    }

    public function sendsAdminNotifications($post)
    {
        $admins = Admin::get();
        Notification::send($admins, new AdminPost(auth()->guard('worker')->user() , $post));
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $post = $this->storePost($request);
            if ($request->hasFile('photos')) {
                $postPhotos = $this->storePostPhotos($request, $post->id);
            }

            $this->sendsAdminNotifications($post);

            DB::commit();
            return response()->json([
                "message" => "post has been created successfuly, your price after discount is {$post->price}"
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => "post has failed " . $e->getMessage(),
            ], 500);
        }
    }
}