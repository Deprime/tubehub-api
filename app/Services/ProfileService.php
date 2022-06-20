<?php

namespace App\Services;

use App\Models\User;

use \App\Requests\Profile\{
  ProfileUpdateRequest
};

class ProfileService {

  /**
   * Update current user profile
   *
   * @param ProfileUpdateRequest $request
   * @param User $user
   * @return User
   */
  public function update(ProfileUpdateRequest $request): User
  {
    $input = $request->validated();
    $user  = $request->user();
    $user->update($input);
    return $user;
  }
}
