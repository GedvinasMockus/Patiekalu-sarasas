<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function sendError($errorData, $message, $status)
    {
        $response = [];
        $response['message'] = $message;
        if (!empty($errorData)) {
            $response['data'] = $errorData;
        }

        return response()->json($response, $status);
    }
    public function index($rid, $mid) {
        // $dishes = Dish::all()->where('menu', '==', $mid);
        $dishes = Dish::where('menu', $mid)->get();

        if(!$dishes->isEmpty()){
            return response()->json($dishes);
        } else {
            return response()->json(["message" => "Dishes not found"], 404);
        }
    }

    public function store(Request $request, $rid, $mid) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:64',
            'description' => 'required|string|min:2|max:256',
            'picture' => 'required|string|url'
        ]);
    
        if ($validator->fails())
            // return response()->json(["message" => "Error in the input data"], 400);
            return $this->sendError($validator->errors(), 'Error in the input data', 400);

        $dish = new Dish;
        $dish->name = $request->name;
        $dish->description = $request->description;
        $dish->picture = $request->picture;
        $dish->menu = $mid;
        $dish->save();
        return response()->json(["message" => "Dish added"], 201);
    }

    public function show($rid, $mid, $did) {
        $dish = Dish::find($did);

        if(!empty($dish)){
            return response()->json($dish);
        } else {
            return response()->json(["message" => "Dish not found"], 404);
        }
    }

    public function update(Request $request, $rid, $mid, $did) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:64',
            'description' => 'required|string|min:2|max:256',
            'picture' => 'required|string'
        ]);
    
        if ($validator->fails())
            // return response()->json(["message" => "Error in the input data"], 400);
            return $this->sendError($validator->errors(), 'Error in the input data', 400);

        if(Dish::where('id', $did)->exists()){
            $dish = Dish::find($did);
            $dish->name = is_null($request->name) ? $dish->name : $request->name;
            $dish->description = is_null($request->description) ? $dish->description : $request->description;
            $dish->picture = is_null($request->picture) ? $dish->picture : $request->picture;
            $dish->menu = $mid;
            $dish->save();

            return response()->json(["message" => "Dish updated"], 200);
        } else {
            return response()->json(["message" => "Dish not found"], 404);
        }
    }

    public function destroy($rid, $mid, $did) {
        if(Dish::where('id', $did)->exists()){
            $dish = Dish::find($did);
            $dish->delete();

            return response()->json(["message" => "Dish deleted"], 200);
        } else {
            return response()->json(["message" => "Dish not found"], 404);
        }
    }
}
