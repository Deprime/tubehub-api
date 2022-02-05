<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
  /**
   * Register the given user.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function signup(Request $request)
  {
    $input = $request->only(['phone', 'code']);
    if (isset($input['phone']))
      $input['phone'] = User::clearPhone($input['phone']);

    $validator = Validator::make($input, Sms::signup_rules());
    if ($validator->fails())
      return response()->json(['errors' => $validator->errors()], 422);

    $sms = Sms::where('phone', $input['phone'])->where('code', $input['code'])->first();
    if (!$sms)
      return response()->json($this->http_responses[404], 404);

    $password = User::generatePassword();
    $user = User::create([
      'phone'    => $input['phone'],
      'password' => Hash::make($password),
      'role'     => Role::PROVIDER,
    ]);

    # Отправляем пароль пользователя
    $sms->sendPassword($password);

    # Удаляем все смски этого пользователя
    Sms::where('phone', $input['phone'])->delete();

    # авторизуем
    $token = auth()->login($user);
    # подгружаем зависимости
    // $user->load('company');

    $token = $this->getToken($token);
    return response()->json(['token' => $token, 'user' => $user], 200);
  }


  /**
   * Signin
   */
  public function signin(Request $request)
  {
    $input = $request->only(['email', 'password']);
    $user  = User::where('email', $input['email'])->first();
    if (!$user || !app('hash')->check($input['password'], $user->password))
      return response()->json([
        'status' => 'error',
        'errors' => ['credentials' => ['Неверный email или пароль']],
        'msg'    => 'Неверный email или пароль.',
      ], 400);

    # авторизуем
    $token = auth()->login($user);
    # подгружаем зависимости
    // $user->load('company');

    $token = $this->getToken($token);
    return response()->json(['token' => $token, 'user' => $user], 200);
  }


  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function me()
  {
    $user = Auth::user();
    // $user->load('company');
    return response()->json(['user' => $user], 200);
  }


  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    auth()->logout();
    return response()->json(['message' => 'Successfully logged out']);
  }


  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh()
  {
    return response()->json(['token' => auth()->refresh(), 'user' => auth()->user()], 200);
  }


  /**
   * Get the token array.
   *
   * @param string $token
   *
   * @return array
   */
  protected function getToken($token)
  {
    return [
      'access_token' => $token,
      'token_type'   => 'bearer',
      'expires_in'   => auth()->factory()->getTTL() * 60 * 24 * 60,
    ];
  }
}
