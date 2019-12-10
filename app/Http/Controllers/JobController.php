<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Job;

class JobController extends Controller
{
  /**
   * Handles logic to fetch all jobs
   *
   * @return \Illuminate\Http\JsonResponse
   * 
   */
  public function index() {

    $jobs = Job::all();

    return response()->json([
      'status' => 'success',
      'data' => $jobs
    ],200);
  }

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
        'status' => 'error',
        'error' => $validation->errors()
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
        'status' => 'success',
        'data' => $job,
        'message' => 'Job successfully created'
      ], 201);
    }

    return response()->json([
      'status' => 'error',
      'error' => 'You are unauthorized, only Admin can perform this operation'
    ], 401);
  }

  /**
   * Handles logic to delete a specific job
   *
   * @param  numeric $id
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function delete($id) {
    $job = Job::find($id);

    if (!$job) {
      return response()->json([
        'status' => 'error',
        'error' => 'Job cannot be found'
      ], 404);
    }

    $job->delete();

    return response()->json([
      'status' => 'success',
      'message' => 'Job successfully deleted'
    ], 200);
  }

  /**
   * Handles logic to update a specific job
   *
   * @param \Illuminate\Http\Request $request
   * @param  numeric $id
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, $id) {

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
        'status' => 'error',
        'error' => $validation->errors()
      ], 422);
    }

    $job = Job::find($id);
    
    if (!$job) {
      return response()->json([
        'status' => 'error',
        'error' => 'Job cannot be found'
      ], 404);
    }
    
    if (Auth::user()->is_admin) {
      
      Job::where('id', $id)
        ->update([
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
        'status' => 'success',
        'message' => 'Job successfully updated'
      ], 201);
    }
  
    return response()->json([
      'status' => 'error',
      'error' => 'You are unauthorized, only Admin can perform this operation'
    ], 401);

  }

  /**
   * Handles logic to fetch a specific job
   *
   * @param  numeric $id
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function fetchOne($id) {
    $job = Job::find($id);
    
    if (!$job) {
      return response()->json([
        'status' => 'error',
        'error' => 'Job cannot be found'
      ], 404);
    }

    return response()->json([
      'status' => 'success',
      'data' => $job
    ], 200);
  }
}
