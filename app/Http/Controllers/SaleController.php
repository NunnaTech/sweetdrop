<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Sale;

class SaleController extends Controller
{
    private $response = ["success" => false, "message" => 'Your sale have not been found', "data" => []];


    public function index()
    {
        $sales = Sale::paginate(15);
        return [
            'success' => true,
            'message' => 'List of sales',
            'data' => $sales
        ];
    }

    public function indexByOrder($id)
    {
        $sales = Sale::with("product")->where('order_id', $id)->paginate(15);
        return [
            'success' => true,
            'message' => 'List of sales',
            'data' => $sales
        ];
    }


    public function store(SaleRequest $request)
    {
        $sale = new Sale([
            'quantity' => $request->quantity,
            'total' => $request->total,
            'order_id' => $request->order_id,
            'product_id' => $request->product_id
        ]);
        $sale->save();
        return [
            'success' => true,
            'message' => 'Your sale have been stored',
            'data' => $sale
        ];
    }


    public function show($id)
    {
        $sale = Sale::with('order', 'product')->where('id', $id)->first();
        if ($sale) return ["success" => true, "message" => 'Your sale have been found', "data" => $sale];
        return $this->response;
    }


    public function update(SaleRequest $request)
    {
        $sale = Sale::query()->where('id', $request->id)->first();
        if ($sale) {
            $sale->quantity = $request->quantity;
            $sale->total = $request->total;
            $sale->order_id = $request->order_id;
            $sale->product_id = $request->product_id;
            $sale->save();
            return [
                'success' => true,
                'message' => 'Your sale have been updated',
                'data' => $sale
            ];
        }
        return $this->response;
    }


    public function destroy($id)
    {
        $sale = Sale::query()->where('id', $id)->first();
        if ($sale) {
            $sale->delete();
            return [
                'success' => true,
                'message' => 'Your sale have been deleted',
                'data' => $sale
            ];
        }
        return $this->response;
    }
}
