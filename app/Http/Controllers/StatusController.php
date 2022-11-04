<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{

    public function index()
    {
        return [
            "success" => true,
            "message" => 'List of status',
            "data" => Status::all()->where('is_active', true)
        ];
    }

    public function show($id)
    {
        $response = ["success" => false, "message" => 'Your status have not been found', "data" => []];
        $status = Status::query()->where('id', $id)->where('is_active', true)->first();
        if ($status) {
            return [
                "success" => true,
                "message" => 'Your status has been found',
                "data" => $status
            ];
        }
        return $response;
    }


}
