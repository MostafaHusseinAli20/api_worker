<?php

namespace App\Services\WorkerResetPasswordService;

use App\Mail\ResetPassword;
use App\Models\Worker;
use Exception;
use Illuminate\Support\Facades\Mail;

class WorkerResetPasswordService 
{
    protected $model;
    public function __construct()
    {
        $this->model = new Worker();
    }

    public function sendEmail($model)
    {
        Mail::to($model->email)->send(new ResetPassword($model));
    }

    public function run()
    {
        try {
            $models = $this->model->all();

            if ($models->isEmpty()) {
                return response()->json([
                    'message' => 'not found'
                ]);
            }
            foreach ($models as $model) {
                $this->sendEmail($model);
            }

            return response()->json([
                'message' => 'Emails sent successfully'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}