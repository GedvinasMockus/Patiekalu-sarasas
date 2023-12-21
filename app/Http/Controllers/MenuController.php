<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
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
    public function index($rid) {
        // $menus = Menu::all()->where('restaurant', '==', $rid); 
        $menus = Menu::where('restaurant', $rid)->get();
        if(!$menus->isEmpty()){
            return response()->json($menus);
        } else {
            return response()->json(["message" => "Menus not found"], 404);
        }
    }

    public function store(Request $request, $rid) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:64',
            'description' => 'required|string|min:2|max:256'
        ]);
    
        if ($validator->fails())
            // return response()->json(["message" => "Error in the input data"], 400);
            return $this->sendError($validator->errors(), 'Error in the input data', 400);

        $menu = new Menu;
        $menu->name = $request->name;
        $menu->description = $request->description;
        $menu->restaurant = $rid;
        $menu->save();
        return response()->json(["message" => "Menu added"], 201);
    }

    public function show($rid, $mid) {
        $menu = Menu::find($mid);

        if(!empty($menu)){
            return response()->json($menu);
        } else {
            return response()->json(["message" => "Menu not found"], 404);
        }
    }

    public function update(Request $request, $rid, $mid) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:64',
            'description' => 'required|string|min:2|max:256'
        ]);
    
        if ($validator->fails())
            // return response()->json(["message" => "Error in the input data"], 400);
            return $this->sendError($validator->errors(), 'Error in the input data', 400);
            
        if(Menu::where('id', $mid)->exists()){
            $menu = Menu::find($mid);
            $menu->name = is_null($request->name) ? $menu->name : $request->name;
            $menu->description = is_null($request->description) ? $menu->description : $request->description;
            $menu->restaurant = $rid;
            $menu->save();

            return response()->json(["message" => "Menu updated"], 200);
        } else {
            return response()->json(["message" => "Menu not found"], 404);
        }
    }

    public function destroy($rid, $mid) {
        if(Menu::where('id', $mid)->exists()){
            $menu = Menu::find($mid);
            $menu->delete();

            return response()->json(["message" => "Menu deleted"], 200);
        } else {
            return response()->json(["message" => "Menu not found"], 404);
        }
    }
}
