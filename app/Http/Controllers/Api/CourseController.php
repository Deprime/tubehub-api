<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{
  User,
  Course
};

class CourseController extends Controller
{
  /**
   * Get
   *
   * @param int $author_id
   * @param int $course_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function get(Request $request, $author_id, $course_id)
  {
    $user_list = User::get();
    return response()->json(['user_list' => $user_list], 200);
  }

  /**
   * List
   *
   * @param int $author_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function list(Request $request, $author_id)
  {
    $user_list = User::get();
    return response()->json(['user_list' => $user_list], 200);
  }
}
