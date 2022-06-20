<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\User;

class AuthService {

  /**
   * Create user
   *
   * @param string $email
   * @param string $password
   * @return User
   */
  public static function createUser($email, $password): User
  {
    $input = [
      'email'    => $email,
      'password' => Hash::make($password),
    ];

    return User::create($input);
  }


  /**
   * Create sanctum token
   *
   * @param User $user
   * @return string
   */
  public static function createToken(Request $request, User $user): string
  {
    if ($user->currentAccessToken()) {
      $user->currentAccessToken()->delete();
    }

    return $user->createToken($request->email)->plainTextToken;
  }

  /**
   * Revoke current access token
   *
   * @param User $user
   * @return boolean
   */
  public static function revokeToken(User $user): boolean
  {
    if ($user->currentAccessToken()) {
      $user->currentAccessToken()->delete();
      return true;
    }
    return false;
  }
}
