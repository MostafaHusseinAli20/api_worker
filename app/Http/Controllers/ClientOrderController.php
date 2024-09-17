<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientOrderRequest;
use App\Interfaces\Client\CrudRepoInterface;
use App\Models\ClientOrder;
use App\Repository\ClientOrderRepo;
use Illuminate\Http\Request;

class ClientOrderController extends Controller
{
    protected $crudRepo;
    function __construct(CrudRepoInterface $crudRepo)
    {
        $this->crudRepo = $crudRepo;
    }

    public function addOrder(ClientOrderRequest $request)
    {
        return $this->crudRepo->store($request);
    }

    public function workerOrder()
    {
        return $this->crudRepo->showAll();
    }

    public function workerOrderByOne($id)
    {
        return $this->crudRepo->showByOne($id);
    }

    public function update($id, Request $request)
    {
        return $this->crudRepo->updateItem($id, $request);
    }
}
