<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use \Illuminate\Http\{
  Request,
  JsonResponse,
};

use App\Models\{
  User,
  Role,
};

class UserController extends Controller
{
  /**
   * Get user list
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function list(Request $request): JsonResponse
  {
    $user_list = User::get();
    return response()->json(['user_list' => $user_list], Response::HTTP_OK);
  }

  /**
   * Method description
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function profile(Request $request): JsonResponse
  {
    $user = $request->user();
    return response()->json(['user' => $user], Response::HTTP_OK);
  }


  /**
   * Get preformer profile
   * @param  Integer $user_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function get(Request $request, int $user_id): JsonResponse
  {
    $performer = User::find($user_id);
    if ($performer) {
      $performer->load('reviews.author');
      return response()->json(['performer' => $performer], Response::HTTP_OK);
    }
    return response()->json([], Response::HTTP_NOT_FOUND);
  }
}
