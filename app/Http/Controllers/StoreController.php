<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Store;

class StoreController extends Controller
{
    private $response = ["success" => false, "message" => 'Your store have not been found', "data" => []];

    public function index()
    {
        $stores = Store::where('is_active', true);
        return [
            'success' => true,
            'message' => 'List of stores',
            'data' => $stores
        ];
    }


    public function store(StoreRequest $request)
    {
        $store = new Store([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'owner' => $request->owner,
        ]);
        $store->save();
        return [
            'success' => true,
            'message' => 'Your store have been stored',
            'data' => $store
        ];
    }


    public function show($id)
    {
        $store = Store::query()->where('id', $id)->where('is_active', true)->first();
        if ($store) return ["success" => true, "message" => 'Your store have been found', "data" => $store];
        return $this->response;
    }


    public function update(StoreRequest $request)
    {
        $store = Store::query()->where('id', $request->id)->where('is_active', true)->first();
        if ($store) {
            $store->name = $request->name;
            $store->phone = $request->phone;
            $store->address = $request->address;
            $store->zipcode = $request->zipcode;
            $store->owner = $request->owner;
            $store->save();
            return [
                'success' => true,
                'message' => 'Your store have been updated',
                'data' => $store
            ];
        }
        return $this->response;
    }


    public function destroy($id)
    {
        $store = Store::query()->where('id', $id)->where('is_active', true)->first();
        if ($store) {
            $store->is_active = false;
            $store->save();
            return [
                'success' => true,
                'message' => 'Your store have been deleted',
                'data' => $store
            ];
        }
        return $this->response;
    }
}