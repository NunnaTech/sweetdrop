<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    private $response = ["success" => false, "message" => 'Your image have not been found', "data" => []];

    public function index()
    {
        $images = Image::paginate(15);
        return [
            'success' => true,
            'message' => 'List of images',
            'data' => $images
        ];
    }
    public function indexByObservation($observationId)
    {
        $images = Image::where('observation_id', $observationId)->paginate(15);
        return [
            'success' => true,
            'message' => 'List of images by Observation',
            'data' => $images
        ];
    }


    public function store(Request $request)
    {
        $image = new Image([
            'image' => $request->image,
            'observation_id' => $request->observation_id,
        ]);
        $image->save();
        return [
            'success' => true,
            'message' => 'Your image have been stored',
            'data' => $image
        ];
    }


    public function show($id)
    {
        $image = Image::query()->where('id', $id)->where('is_active', true)->first();
        if ($image) return ["success" => true, "message" => 'Your image have been found', "data" => $image];
        return $this->response;
    }

    public function destroy($id)
    {
        $image = Image::query()->where('id', $id)->first();
        if ($image) {
            $image->delete();
            return [
                'success' => true,
                'message' => 'Your store have been deleted',
                'data' => $image
            ];
        }
        return $this->response;
    }
}
