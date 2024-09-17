<?php

namespace App\Repository;

use App\Interfaces\Client\CrudRepoInterface;
use App\Models\ClientOrder;
use Illuminate\Http\Request;

class ClientOrderRepo implements CrudRepoInterface 
{
    public function store($request)
    {
        $clientId = auth()->guard('client')->id();
        if(ClientOrder::where('client_id', $clientId)->where('post_id', $request->post_id)->exists())
        {
            return response()->json([
                'message' => 'This Post already exists',
            ], 406);
        }
        $data = $request->all();
        $data['client_id'] = $clientId;
        $order = ClientOrder::create($data);

        return response()->json([
            'message' => 'Success',
        ], 201);
    }

    public function showAll()
    {
        $order = ClientOrder::with('post', 'client')->whereStatus('pending')->whereHas('post', function($query){
            $query->where('worker_id', auth()->guard('worker')->id());
        })->get();

        return response()->json([
            'orders' => $order
        ]);
    }

    public function showByOne($id)
    {
        $orderByOne = ClientOrder::with('client', 'Post')->find($id);

        return response()->json([
            'order' => $orderByOne
        ]);
    }


    public function updateItem($id, Request $request) 
    {
        $order = ClientOrder::findOrFail($id);
        $order->setAttribute('status', $request->status)->save();
        return response()->json([
            'message' => 'Updated Successfuly'
        ]);
    }
}