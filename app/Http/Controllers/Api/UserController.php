<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}
