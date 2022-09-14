<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // validates user data
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',        
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ];

            // create account
            $user = User::create($data);

            if ($user!=null) {
                return $this->sendSuccess($user, 'success');
            } else {
                return $this->sendError($user = [], 'Unable to create account. Please try again');
            }
     
        } catch (\Exception $e) {
            return $this->sendError($user = [], $e->getMessage());
        }
    }
 
    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        try {
            if (auth()->attempt($data)) {
                $user['token'] = auth()->user()->createToken('UserAccount')->accessToken;
                $user['user'] = auth()->user();
                return $this->sendSuccess($user, 'success');
            } else {
                return $this->sendError($user = [], 'Invalid Email/Password');
            }
        } catch (\Exception $e) {
            return $this->sendError($user = [], $e->getMessage());
        }
    }  
}
