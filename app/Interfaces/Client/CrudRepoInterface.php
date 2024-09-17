<?php

namespace App\Interfaces\Client;

use Illuminate\Http\Request;

interface CrudRepoInterface {
    public function store($request);
    public function showAll();
    public function showByOne($id);
    public function updateItem($id, Request $request);
}