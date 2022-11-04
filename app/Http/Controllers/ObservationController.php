<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObservationRequest;
use App\Models\Observation;

class ObservationController extends Controller
{
    private $response = ["success" => false, "message" => 'Your observation have not been found', "data" => []];


    public function index()
    {
        $observations = Observation::where('is_active', true)->paginate(15);
        return [
            'success' => true,
            'message' => 'List of observations',
            'data' => $observations
        ];
    }

    public function indexByOrder($orderId)
    {
        $observations = Observation::where('order_id', $orderId)->
            where('is_active', true)
            ->paginate(15);
        return [
            'success' => true,
            'message' => 'List of observations by order',
            'data' => $observations
        ];
    }


    public function store(ObservationRequest $request)
    {
        $observation = new Observation([
            'comment' => $request->comment,
            'order_id' => $request->order_id,
        ]);
        $observation->save();
        return [
            'success' => true,
            'message' => 'Your observation have been stored',
            'data' => $observation
        ];
    }


    public function show($id)
    {
        $observation = Observation::query()->where('id', $id)->where('is_active', true)->first();
        if ($observation) return ["success" => true, "message" => 'Your observation have been found', "data" => $observation];
        return $this->response;
    }



    public function update(ObservationRequest $request)
    {
        $observation = Observation::query()->where('id', $request->id)->where('is_active', true)->first();
        if ($observation) {
            $observation->comment = $request->comment;
            $observation->save();
            return [
                'success' => true,
                'message' => 'Your observation have been updated',
                'data' => $observation
            ];
        }
        return $this->response;
    }


    public function destroy($id)
    {
        $observation = Observation::query()->where('id', $id)->where('is_active', true)->first();
        if ($observation) {
            $observation->is_active = false;
            $observation->save();
            return [
                'success' => true,
                'message' => 'Your store have been deleted',
                'data' => $observation
            ];
        }
        return $this->response;
    }
}
