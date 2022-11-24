<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Image;
use App\Models\Observation;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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


    public function finishOrder($id)
    {
        try {
            $order = Order::query()->where('id', $id)->where('is_active', true)->first();
            if ($order) {
                $order->update([
                    'status_id' => 3,
                    'deliver_date' => now()
                ]);
                $this->response['success'] = true;
                $this->response['message'] = 'Order finished';
                $this->response['data'] = $order;
            } else {
                $this->response['message'] = 'Order not found';
            }
            return $this->response;
        } catch (\Exception $e) {
            $this->response['success'] = false;
            $this->response['message'] = 'Error al finalizar la orden';
            return $this->response;
        }
    }

    public function storeVisit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric',
            'comment' => 'required|string',
            'images' => 'array',

        ]);
        if (!$validator->fails()) {
            try {
                DB::beginTransaction();
                $user = auth()->user();
                $order = new Order([
                    'folio' => $this->generateFolio("VST"),
                    'request_date' => now(),
                    'is_completed' => true,
                    'deliver_date' => now(),
                    'delivered_by' => $user->id,
                    'store_id' => $request->store_id,
                    'status_id' => 1,
                ]);
                $order->save();
                $observation = new Observation([
                    'comment' => $request->comment,
                    'order_id' => $order->id,
                ]);
                $observation->save();
                $images = $request->images;
                foreach ($images as $img) {
                    $image = new Image([
                        'image' => $img,
                        'observation_id' => $observation->id,
                    ]);
                    $image->save();
                }
                DB::commit();
                $this->response['success'] = true;
                $this->response['message'] = 'Visit registered';
                $this->response['data'] = [
                    'order' => $order,
                    'observation' => $observation,
                ];
                return $this->response;
            } catch (\Exception $e) {
                $this->response['message'] = 'The visit could not be created';
                DB::rollBack();
                return $this->response;
            }
        } else {
            DB::rollBack();
            $this->response['message'] = 'The fields are not valid';
            return $this->response;
        }
    }

    private function generateFolio($type)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 8; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $type . '-' . $randomString . '-' . date('Y');
    }

    public function store(OrderRequest $request)
    {
        try {
            $user = auth()->user();
            DB::beginTransaction();
            $order = new Order([
                'folio' => $this->generateFolio("ORD"),
                'request_date' => now(),
                'received_by' => $request->received_by,
                'delivered_by' => $user->id,
                'store_id' => $request->store_id,
                'status_id' => 2,
            ]);
            $order->save();
            $products = $request->products;
            foreach ($products as $product) {
                $productFound = Product::query()->where('id', "=", $product['id'])->first();
                $sale = new Sale([
                    'quantity' => $product['quantity'],
                    'total' => $productFound->price * $product['quantity'],
                    'product_id' => $product['id'],
                    'order_id' => $order->id,
                ]);
                $sale->save();
            }
            $observation = new Observation([
                'comment' => $request->comment,
                'order_id' => $order->id,
            ]);
            $observation->save();
            $images = $request->images;
            foreach ($images as $img) {
                $image = new Image([
                    'image' => $img,
                    'observation_id' => $observation->id,
                ]);
                $image->save();
            }

            $orderTotalSales = Sale::query()->where('order_id', '=', $order->id)->sum('total');
            $order->total = floatval($orderTotalSales);
            $order->save();
            DB::commit();
            $this->response['success'] = true;
            $this->response['message'] = 'Order registered';
            $this->response['data'] = $order;
            return $this->response;
        } catch (\Exception $e) {
            $this->response['message'] = $e->getMessage();
            DB::rollBack();
            return $this->response;
        }

    }


    public function show($id)
    {
        $order = Order::with("delivered", "store", "status", "sales", "observations")
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
        if ($order) {
            $this->response['success'] = true;
            $this->response['message'] = 'Order found';
            $this->response['data'] = $order;
        };
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
