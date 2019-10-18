<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
  public function signUp(Request $request) {

    
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
  
      return response()->json(['data' => $user, 'message' => 'Successful User Registration'], 201);
    } catch (\Exception $ex) {
      return response()->json(['error' => $ex, 'message' => 'Unsuccessful User Registration'], 422);
    }
  }
}
