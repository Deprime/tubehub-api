<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Role;

class SanctumController extends Controller
{
  /**
   * Signup via email
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function signupEmail(Request $request)
  {
    $input     = $request->only(['email', 'password']);
    $validator = Validator::make($input, User::email_signup_rules());

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = User::create([
      'email'    => $input['email'],
      'password' => Hash::make($input['password']),
      // 'role'     => Role::PERFORMER,
    ]);

    if ($user->currentAccessToken()) {
      $user->currentAccessToken()->delete();
    }
    $token = $user->createToken($request->email)->plainTextToken;
    return response()->json(['token' => $token], 200);
  }

  /**
   * Finalize
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function finalize(Request $request)
  {
    $role = $request->only(['role']);

    // Role validation
    $validator = Validator::make($role, User::role_rules());
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $fields = [
      Role::CUSTOMER  => ['role', 'nickname'],
      Role::PERFORMER => ['role', 'first_name', 'last_name', 'birthdate'],
    ];

    // Profile fields validation
    $rules = ($input['role'] === Role::CUSTOMER)
           ? User::customer_rules()
           : User::performer_rules();
    $input = $request->only($fields[$role]);

    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = $request->user();
    $user->update($input);
    return response()->json(['user' => $user], 200);
  }

  /**
   * Email signin
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function token(Request $request)
  {
    $input     = $request->only(['email', 'password']);
    $validator = Validator::make($input, User::email_signin_rules());

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
      return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
    }

    if ($user->currentAccessToken()) {
      $user->currentAccessToken()->delete();
    }

    $token = $user->createToken($request->email)->plainTextToken;
    return response()->json(['token' => $token], 200);
  }
}
