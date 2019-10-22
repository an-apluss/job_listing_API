<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Job;

class JobController extends Controller
{
  /**
   * Handles the logic to create a new job 
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function create(Request $request) {
    
    $validation = Validator::make($request->all(), [
      'title' => 'string|required', 
      'company' => 'string|required', 
      'description' => 'string|required', 
      'location' => 'string|required', 
      'industry' => 'string|required', 
      'job_type' => 'string|required', 
      'salary' => 'numeric|required'
    ]);

    if ($validation->fails()) {
      return response()->json([
        'error' => $validation->errors()->toJson(),
        'success' => false
      ], 422);
    }
    
    if (Auth::user()->is_admin) {
      $job = Job::create([
        'title' => $request->title, 
        'company' => $request->company, 
        'description' => $request->description, 
        'user_id' => Auth::user()->id, 
        'location' => $request->location, 
        'industry' => $request->industry, 
        'job_type' => $request->job_type, 
        'salary' => $request->salary
      ]);

      return response()->json([
        'data' => $job,
        'message' => 'Job successfully created',
        'success' => true
      ], 201);
    }

    return response()->json([
      'error' => 'You are unauthorized, only Admin can perform this operation',
      'message' => 'Job cannot be created',
      'success' => false
    ], 401);
  }
}
