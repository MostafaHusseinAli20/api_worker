<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Worker;
use App\Services\WorkerServices\WorkerLoginService\WorkerLoginService;
use App\Services\WorkerServices\WorkerRegisterService\WorkerRegisterService;

use Illuminate\Support\Facades\Auth;
use Validator;


class WorkerAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:worker', ['except' => ['login', 'register' , 'verify']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request){
        return (new WorkerLoginService())->login($request);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request) {
        return (new WorkerRegisterService())->register($request);
    }

    function verify($token)
    {
        $worker = Worker::whereVerificationToken($token)->first();
        if(!$worker)
        {
            return response()->json([
                'message' => 'this token is invalid'
            ], 404);
        }

        $worker->verification_token = null;
        $worker->verified_at = now();
        $worker->save();
        return response()->json([
            'message' => 'token is verified'
        ], 202);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->guard('worker')->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->guard('worker')->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->guard('worker')->user()->get(['id', 'name', 'phone'
    ,'photo', 'location']));
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('worker')->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user()
        ]);
    }

}