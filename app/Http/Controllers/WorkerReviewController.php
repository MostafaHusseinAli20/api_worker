<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerReviewRequest;
use App\Http\Resources\WorkerReviewResource;
use App\Interfaces\worker\WorkerReviewInterface;
use Illuminate\Http\Request;

class WorkerReviewController extends Controller
{
    protected $review;
    public function __construct(WorkerReviewInterface $review)
    {
        $this->review = $review;
    }
    public function addComment(WorkerReviewRequest $request)
    {
        return $this->review->store($request);
    }

    public function postReviewByOne($id)
    {
        return $this->review->postReview($id);
    }
}
