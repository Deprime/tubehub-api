<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Http\JsonResponse;

use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
  /**
   * Get user list
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function list(Request $request)
  {
    $user_list = User::get();
    return response()->json(['user_list' => $user_list], 200);
  }

  /**
   * Method description
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function profile(Request $request)
  {
    $user = $request->user();
    return response()->json(['user' => $user], 200);
  }


  /**
   * Get preformer profile
   * @param  Integer $user_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function get(Request $request, int $user_id) {
    $performer = User::find($user_id);
    if ($performer) {
      $performer->load('reviews.author');
      return response()->json(['performer' => $performer], Response::HTTP_OK);
    }
    else {
      return response()->json(['performer' => $performer], Response::HTTP_NOT_FOUND);
    }
  }
}
