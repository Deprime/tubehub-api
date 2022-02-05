<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\{
  HasMany,
  HasOne,
  BelongsTo,
  BelongsToMany,
};

use Carbon\Carbon;

class Author extends User
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'users';

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
    'reset_token',
    'is_active',
    'deleted_at',
    'email_verified_at',
    'email',
    'phone',
    'phone_verified_at',
    'role',
    'birthdate',
    'sex',
    'patronymic',
    'created_at',
    'updated_at',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'birth_date' => 'date:' . self::CUSTOM_DATE_FORMAT,
  ];

  /**
   * The attributes that should be appended.
   *
   * @var array<int, string>
   */
  protected $appends = [
    'initials',
    'litera',
    'fl_name',
  ];

  /**
   * Reviews
   */
  public function Reviews(): HasMany
  {
    return $this->hasMany(Review::class, 'recipient_id', 'id');
  }
}
