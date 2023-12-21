<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
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
    public function index() {
        $restaurants = Restaurant::all();

        if(!$restaurants->isEmpty()){
            return response()->json($restaurants);
        } else {
            return response()->json(["message" => "Restaurants not found"], 404);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:64',
            'status' => 'required|string|min:2|max:64',
            'owner' => 'required|int'
        ]);
    
        if ($validator->fails())
            // return response()->json(["message" => "Error in the input data"], 400);
            return $this->sendError($validator->errors(), 'Error in the input data', 400);

        $restaurant = new Restaurant;
        $restaurant->name = $request->name;
        $restaurant->status = $request->status;
        $restaurant->owner = $request->owner;
        $restaurant->save();
        return response()->json(["message" => "Restaurant added"], 201);
    }

    public function show($rid) {
        $restaurant = Restaurant::find($rid);

        if(!empty($restaurant)){
            return response()->json($restaurant);
        } else {
            return response()->json(["message" => "Restaurant not found"], 404);
        }
    }

    public function update(Request $request, $rid) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:64',
            'status' => 'required|string|min:2|max:64',
            'owner' => 'required|int'
        ]);
    
        if ($validator->fails())
            return $this->sendError($validator->errors(), 'Error in the input data', 400);
            
        if(Restaurant::where('id', $rid)->exists()){
            $restaurant = Restaurant::find($rid);
            $restaurant->name = is_null($request->name) ? $restaurant->name : $request->name;
            $restaurant->status = is_null($request->status) ? $restaurant->status : $request->status;
            $restaurant->owner = is_null($request->owner) ? $restaurant->owner : $request->owner;
            $restaurant->save();

            return response()->json(["message" => "Restaurant updated"], 200);
        } else {
            return response()->json(["message" => "Restaurant not found"], 404);
        }
    }

    public function destroy($rid) {
        if(Restaurant::where('id', $rid)->exists()){
            $restaurant = Restaurant::find($rid);
            $restaurant->delete();

            return response()->json(["message" => "Restaurant deleted"], 200);
        } else {
            return response()->json(["message" => "Restaurant not found"], 404);
        }
    }
}
