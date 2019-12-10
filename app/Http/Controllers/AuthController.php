<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\User;

class AuthController extends Controller
{
  /**
   * Handles the logic to create a new user.
   *
   * @param  Illuminate\Http\Request
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function signUp(Request $request) 
  {
    $validation = Validator::make($request->all(), [
      'first_name' => 'required|string', 
      'last_name' => 'required|string', 
      'user_name' => 'required|string|unique:users',
      'password' => 'required|alpha_num|min:6',
    ]);

    if ($validation->fails()) {
      return response()->json([
        'status' => 'error',
        'error' => $validation->errors()
      ], 422);
    }
    
    try {
      $user = User::create([
        'first_name' => $request->first_name, 
        'last_name' => $request->last_name, 
        'user_name' => $request->user_name,
        'password' => Hash::make($request->password),
        'is_admin' => false 
      ]);
  
      return response()->json([
        'status' => 'success',
        'data' => $user, 
        'message' => 'User successfully created'
      ], 201);
      
    } catch (\Exception $ex) {
      return response()->json([
        'status' => 'error',
        'error' => $ex, 
        'message' => 'Unsuccessful User Registration'
      ], 422);

    }
  }

  /**
   * Handle logic to log in user and generate a token.
   *
   * @param  Illuminate\Http\Request
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function signin(Request $request)
  {
    $validation = Validator::make($request->all(), [
      'user_name' => 'required|string',
      'password' => 'required|alpha_num' 
    ]);

    if ($validation->fails()) {
      return response()->json([
        'status' => 'error',
        'error' => $validation->errors(),
      ], 422);
    }

    $user = $request->only(['user_name', 'password']);

    if(!$token = Auth::attempt($user)) {
      return response()->json([
        'status' => 'error',
        'error' => 'Unauthorized credential provided'
      ], 401);
    }
    
    return $this->respondWithToken($token);
  }

  /**
   * Get the token array structure.
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($token)
  {
    return response()->json([
      'status' => 'success',
      'access_token' => $token,
      'expires_in' => Auth::factory()->getTTL() * 60
    ], 200);
  }
}
