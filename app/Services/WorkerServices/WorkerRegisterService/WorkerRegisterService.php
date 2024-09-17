<?php

namespace App\Services\WorkerServices\WorkerRegisterService;

use App\Mail\VerifictionEmail;
use App\Models\Worker;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery\Expectation;

class WorkerRegisterService
{
    protected $model;
    function __construct()
    {
        $this->model = new Worker();
    }

    function validation($request) 
    {
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return $validator;
    }

    function store($data , $request) 
    {
        $worker = $this->model->create(array_merge(
            $data->validated(),
            [
                'password' => bcrypt($request->password),
                'photo' => $request->file('photo')->store('workers'),
            ]
        ));
        return $worker->email;
    }

    function generateToken($email)
    {
        $token = substr(md5(rand(0,9). $email . time()), 0, 32);
        $worker = $this->model->whereEmail($email)->first();
        $worker->verification_token = $token;
        $worker->status = 1;
        $worker->save();
        return $worker;
    }

    // function sendEmail($worker)
    // {
    //     Mail::to($worker->email)->send(new VerifictionEmail($worker));
    // }

    function register($request)
    {
        try {
            DB::beginTransaction();
            $data = $this->validation($request);
            $email = $this->store($data, $request);
            $worker = $this->generateToken($email);
            //$this->sendEmail($worker);
            DB::commit();
            return response()->json([
                "message" => "acc has been created, please check your email"
            ], 201);
        } catch (Exception $e) {
            return $e->getMessage();
            DB::rollBack();
        }
    }
}