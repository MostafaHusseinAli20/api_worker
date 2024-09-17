<?php

namespace App\Services\PaymentServices;

use App\Interfaces\Payment\PaymentInterface;
use App\Models\Admin;
use App\Models\AdminPercent;
use App\Models\Post;
use App\Models\WorkerCache;
use Illuminate\Support\Facades\DB;

class PaymentService implements PaymentInterface
{
    public function payment($request, $stripe, $serviceId)
    {
        try {
            DB::beginTransaction();
            $post = Post::find($serviceId);

            if (!$post || !$post->price) {
                return response()->json([
                    'error' => 'Product not found'
                ], 404);
            }

            if($post->status == 'pending') {
                return response()->json([
                    'message' => 'This Product is Pending'
                ], 405);
            }

            $unit_amount = $post->price * 100;
            $admin_amount = $unit_amount * (0.05 / 100);

            WorkerCache::create([
                'client_id' => auth()->guard('client')->id(),
                'post_id' => $post->id,
                'total' => $post->price,
            ]);

            AdminPercent::create([
                'post_id' => $post->id,
                'total' => $admin_amount
            ]);

            $session = $stripe->checkout->sessions->create([
                'line_items' => [
                    [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $post->content,
                        ],
                        'unit_amount' => $unit_amount,
                    ],
                    'quantity' => 1,
                ]
            ],
                'mode' => 'payment',
                'success_url' => url('/success'),
                'cancel_url' => url('/cancel'),
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Payment session created successfully',
                'session_url' => $session->url,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Payment creation failed: ' . $e->getMessage()
            ], 500
            );
        }
    }
}
