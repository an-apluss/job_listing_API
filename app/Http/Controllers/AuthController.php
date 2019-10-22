<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

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
    $this->validate($request, [
      'first_name' => 'required|string', 
      'last_name' => 'required|string', 
      'user_name' => 'required|string|unique:users',
      'password' => 'required|alpha_num|min:6',
    ]);
    
    try {
      $user = User::create([
        'first_name' => $request->first_name, 
        'last_name' => $request->last_name, 
        'user_name' => $request->user_name,
        'password' => Hash::make($request->password),
        'is_admin' => false 
      ]);
  
      return response()->json([
        'data' => $user, 
        'message' => 'Successful User Registration', 
        'success' => true
      ], 201);
      
    } catch (\Exception $ex) {
      return response()->json([
        'error' => $ex, 
        'message' => 'Unsuccessful User Registration', 
        'success' => false
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
    $this->validate($request, [
      'user_name' => 'required|string',
      'password' => 'required|alpha_num' 
    ]);

    $user = $request->only(['user_name', 'password']);

    if(!$token = Auth::attempt($user)) {

      return response()->json([
        'message' => 'Unauthorized credential provided',
        'success' => false
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
      'access_token' => 'Bearer '.$token,
      'expires_in' => Auth::factory()->getTTL() * 60,
      'success' => true
    ], 200);
  }
}
