<?php

namespace App\Repository;

use App\Http\Requests\WorkerReviewRequest;
use App\Http\Resources\WorkerReviewResource;
use App\Interfaces\worker\WorkerReviewInterface;
use App\Models\WorkerReview;

class WorkerReviewRepo implements WorkerReviewInterface 
{
    public function store(WorkerReviewRequest $request)
    {
        $data = $request->all();
        $data['client_id'] = auth()->guard('client')->id();
        $review = WorkerReview::create($data);
        return response()->json([
            'message' => $review
        ], 201);
    }
    public function postReview($id){
        $review = WorkerReview::wherePostId($id);
        $avrage = $review->sum('rate') / $review->count();

        return response()->json([
            'total_rate' => round($avrage, 2),
            'data' => WorkerReviewResource::collection($review->get())
        ]);
    }
}