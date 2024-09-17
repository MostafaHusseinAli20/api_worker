<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\UpdateWorkerProfileRequest;
use App\Models\Post;
use App\Models\Worker;
use App\Models\WorkerReview;
use App\Services\WorkerServices\WorkerProfile\UpdateWorkerProfileService;
use Illuminate\Http\Request;

class WorkerProfileController extends Controller
{
    public function showProfile()
    {
        $workerId = auth()->guard('worker')->id();
        $worker = Worker::with('posts.reviews')->find($workerId)->makeHidden('status','verification_token'
    ,'verified_at');
        $reivew = WorkerReview::whereIn('post_id', $worker->posts()->pluck('id'));
        $rate = round($reivew->sum('rate') / $reivew->count(), 1);

        return response()->json([
            'data' => array_merge($worker->toArray(), ['rate' => $rate])
        ]);
    }

    // edit on post
    public function edit()
    {
        return response()->json(['worker' => Worker::find(auth()->guard('worker')->id())->makeHidden('status','verification_token'
        ,'verified_at')]);
    }

    public function update(UpdateWorkerProfileRequest $request)
    {
        return (new UpdateWorkerProfileService())->update($request);
    }

    public function destroy()
    {
        Post::where('worker_id', auth()->guard('worker')->id())->delete();
        return response()->json([
            'message' => 'Posts deleted successfully'
        ]);
    }
}
