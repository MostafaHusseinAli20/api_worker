<?php

namespace App\Interfaces\worker;

use App\Http\Requests\WorkerReviewRequest;

interface WorkerReviewInterface {
    public function store(WorkerReviewRequest $request);
    public function postReview($id);
}