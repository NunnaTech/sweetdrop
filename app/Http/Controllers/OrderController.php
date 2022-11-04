<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;

class OrderController extends Controller
{
    private $response = ["success" => false, "message" => 'Your store have not been found', "data" => []];

    public function index()
    {
        $ordes = Order::where('is_active', true)->paginate(15);
        return [
            'success' => true,
            'message' => 'List of orders',
            'data' => $ordes
        ];
    }

    public function indexByUser($id)
    {
        //
    }


    public function store(OrderRequest $request)
    {
        $order = new Order([
            'folio' => $request->folio,
            'request_date' => $request->request_date,
            'deliver_date' => $request->deliver_date,
            'total' => $request->total,
            'received_by' => $request->received_by,
            'delivered_by' => $request->delivered_by,
            'store_id' => $request->store_id,
            'status_id'=>1
        ]);
        $order->save();
        return [
            'success' => true,
            'message' => 'Your order have been stored',
            'data' => $order];
    }


    public function show($id)
    {
        $order = Order::with("delivered","store","status")
            ->where('id', $id)->where('is_active', true)->first();
        if ($order) return ["success" => true, "message" => 'Your order have been found', "data" => $order];
        return $this->response;
    }


    public function update(OrderRequest $request)
    {
        $order = Order::query()->where('id', $request->id)->where('is_active', true)->first();
        if ($order) {
            $order->folio = $request->folio;
            $order->request_date = $request->request_date;
            $order->deliver_date = $request->deliver_date;
            $order->total = $request->total;
            $order->received_by = $request->received_by;
            $order->delivered_by = $request->delivered_by;
            $order->status_id = $request->status_id;
            $order->save();
            return [
                'success' => true,
                'message' => 'Your order have been updated',
                'data' => $order
            ];
        }
        return $this->response;
    }

    public function destroy(Order $order, $id)
    {
        $order = Order::query()->where('id', $id)->where('is_active', true)->first();
        if ($order) {
            $order->is_active = false;
            $order->save();
            return [
                'success' => true,
                'message' => 'Your order have been deleted',
                'data' => $order
            ];
        }
        return $this->response;
    }
}
