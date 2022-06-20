<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\{
  Hash,
  Validator,
  Auth
};

use App\Services\{
  AuthService
};

use App\Http\Requests\Auth\{
  SigninRequest,
  SignupEmailRequest
};

use App\Models\{
  User,
  Role,
};

class SanctumController extends Controller
{
  /**
   * Signup via email
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function signupEmail(SignupEmailRequest $request): JsonResponse
  {
    $input = $request->validated();

    try {
      $user  = AuthService::createUser($input['email'], $input['password']);
    }
    catch (\Exception $exception) {
      return response()->json(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY );
    }

    $token = AuthService::createToken($request, $user);
    return response()->json(['token' => $token]);
  }

  /**
   * Signin
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function signin(SigninRequest $request): JsonResponse
  {
    $input = $request->validated();
    if (!Auth::attempt($input)) {
      return response()->json(['error' => 'The provided credentials are incorrect.'], Response::HTTP_UNAUTHORIZED);
    }

    /** @var User $user */
    $user  = $request->user();
    $token = AuthService::createToken($request, $user);

    return response()->json(['token' => $token, 'user' => $user]);
  }

  /**
   * Logout
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout(Request $request): JsonResponse
  {
    $user = $request->user();
    if ($user) {
      AuthService::revokeToken($user);
    }
    return response()->json([], Response::HTTP_NO_CONTEN);
  }
}
