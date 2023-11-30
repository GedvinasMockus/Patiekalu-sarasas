<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function sendResponse($data, $message, $status = 200) 
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response, $status);
    }

    public function sendError($errorData, $message, $status)
    {
        $response = [];
        $response['message'] = $message;
        if (!empty($errorData)) {
            $response['data'] = $errorData;
        }

        return response()->json($response, $status);
    }

    public function register(Request $request) 
    {
        $input = $request->only('name', 'surname', 'email', 'password', 'c_password', 'role');

        $validator = Validator::make($input, [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 'Validation Error', 422);
        }

        $input['role'] = "Regular";
        $input['password'] = bcrypt($input['password']); // use bcrypt to hash the passwords
        $user = User::create($input); // eloquent creation of data

        $success['user'] = $user;

        return $this->sendResponse($success, 'user registered successfully', 201);

    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');

        $validator = Validator::make($input, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $input['email'])->first();

        if($validator->fails()){
            return $this->sendError($validator->errors(), 'Validation Error', 422);
        }

        try {
            // this authenticates the user details with the database and generates a token
            if (! $token = JWTAuth::claims(['role' => $user->role])->attempt($input) ) {
                return $this->sendError([], "invalid login credentials", 400);
            }
        } catch (JWTException $e) {
            return $this->sendError([], $e->getMessage(), 401);
        }
 
        $success = [
            'token' => $token,
        ];
        return $this->sendResponse($success, 'successful login', 200);
    }

    public function refresh(Request $request)
    {
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token);

        $success = [
            'token' => $newToken,
        ];
        return $this->sendResponse($success, 'successful refresh', 200);
    }
    public function getUser() 
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->sendError([], "user not found", 403);
            } 
        } catch (JWTException $e) {
            return $this->sendError([], $e->getMessage(), 401);
        }

        return $this->sendResponse($user, "user data retrieved", 200);
    }

    public function index() {
        $users = User::all();

        if(!$users->isEmpty()){
            return response()->json($users);
        } else {
            return response()->json(["message" => "Users not found"], 404);
        }
    }

    public function changeRole(Request $request, $uid) {
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|min:2|max:64',
        ]);
    
        if ($validator->fails())
            return response()->json(["message" => "Error in the input data"], 400);

        if($request->role != "Regular" && $request->role != "Admin" && $request->role != "Owner"){
            return response()->json(["message" => "No such role exists"], 400);
        }
            
        if(User::where('id', $uid)->exists()){
            $restaurant = User::find($uid);
            $restaurant->role = is_null($request->role) ? $restaurant->role : $request->role;
            $restaurant->save();

            return response()->json(["message" => "User updated"], 200);
        } else {
            return response()->json(["message" => "User not found"], 404);
        }
    }
}