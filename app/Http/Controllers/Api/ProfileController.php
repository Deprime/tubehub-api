<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use \Illuminate\Http\{
  Request,
  JsonResponse,
};

use App\Services\ProfileService;

use \App\Requests\Profile\{
  ProfileUpdateRequest
};

use App\Models\{
  User,
  Role,
};

class ProfileController extends Controller
{
  /**
   * Get current user profile
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function get(Request $request): JsonResponse
  {
    $user = $request->user();
    return response()->json(['user' => $user]);
  }


  /**
   * Update current user profile
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(ProfileUpdateRequest $request, ProfileService $profile_service): JsonResponse
  {
    $user = $profile_service->update($request);
    return response()->json(['user' => $user]);
  }
}
