<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Validation\Rule;


use Illuminate\Database\Eloquent\Relations\{
  HasMany,
  HasOne,
  BelongsTo,
  BelongsToMany,
};

use Carbon\Carbon;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'users';

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'phone',
    'email',
    'email_verified_at',
    'password',
    'reset_token',
    'first_name',
    'last_name',
    'patronymic',
    'birthdate',
    'role',
  ];

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
    'deleted_at'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'is_active'         => 'boolean',
    'email_verified_at' => 'datetime',
    'birth_date'        => 'date:' . self::CUSTOM_DATE_FORMAT,
  ];

  /**
   * The attributes that should be appended.
   *
   * @var array<int, string>
   */
  protected $appends = [
    'initials',
    'litera',
    // 'fio_full',
    'fl_name',
    'hash',
  ];


  /**
   * Email signup rules
   *
   * @return array
   */
  protected static function email_signup_rules()
  {
    return [
      'email'    => 'required|string|email:rfc,strict,filter|unique:users',
      // 'password' => 'required|alpha_dash|min:6',
      'password' => [
        'required',
        'min:6',
        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
      ],
    ];
  }

  /**
   * Email signin rules
   * @return array
   */
  protected static function email_signin_rules()
  {
    return [
      'email'    => 'required|string|email:rfc,strict,filter',
      'password' => ['required','min:6',],
    ];
  }


  protected static function role_rules()
  {
    return [
      'role' => ['required', 'string', Rule::in(Role::USER_ROLES),],
    ];
  }

  /**
   * Customer rules
   * @return array
   */
  protected static function customer_rules()
  {
    return [
      'role'     => 'required|string',
      'nickname' => 'required|string|min:6',
    ];
  }

  /**
   * Performer rules
   * @return array
   */
  protected static function rerformer_rules()
  {
    return [
      'role'     => 'required|string',
      'nickname' => 'required|string|min:6',
    ];
  }

  /**
   * Signup user rules
   *
   * @return array
   */
  protected static function signup_rules()
  {
    return [
      'first_name'  => 'required|string|min:2',
      'last_name'   => 'required|string|min:2',
      'email'       => 'required|string|email:rfc,strict,filter|unique:users',
      'password'    => 'required|string|min:6',
    ];
  }

  /**
   * Create user rules
   *
   * @return array
   */
  protected static function create_rules()
  {
    return [
      'first_name'  => 'required|string|min:2',
      'last_name'   => 'required|string|min:2',
      'email'       => 'required|string|email:rfc,strict,filter|unique:users',
      'password'    => 'required|string|min:6',
    ];
  }

  /**
   * User update rules by admin
   *
   * @return array
   */
  protected function client_update_rules($user_id)
  {
    return [
      'email'      => 'required|string|email:rfc,strict,filter|unique:users,email,'.$user_id,
      'role'       => ['required', 'string', Rule::in(self::roleKeysList()),],
      'password'   => ['required_if:change_password,1', 'nullable', 'string','min:6'],
			'first_name' => 'required|alpha|min:2',
      'last_name'  => 'required|alpha|min:2',
      'patronymic' => 'required|alpha|min:2',
    ];
  }

  protected static function unique_phone()
  {
    return [
      'phone' => 'required|unique:users,phone|digits:10'
    ];
  }

  protected static function no_unique_phone()
  {
    return [
      'phone' => 'required|digits:10'
    ];
  }

  /**
   * Password update rules
   *
   * @return array
   */
  protected function password_update_rules()
  {
    return [
			'password' => 'required|string|min:6',
    ];
  }

  /**
   * Role keys list
   *
   * @return array
   */
  public static function roleKeysList()
	{
    return array_keys(self::roleList());
  }


  /**
   * Role list
   *
   * @return array
   */
  public static function roleList()
	{
    return [
      Role::DEMENTOR  => 'Администратор',
      Role::MODERATOR => 'Модератор',
      Role::CUSTOMER  => 'Заказчик',
      Role::PERFORMER => 'Исполнитель',
    ];
  }

  /**
   * Role list as array
   *
   * @return array
   */
  public static function getRoleList() {
    $role_list = [];
    foreach (self::roleList() as $key => $value) {
      $role_list[] = ['tag' => $key, 'title' => $value];
    }
    return $role_list;
  }

  /**
   * Атрибут наименования роли
   */
	public function getRoleTitleAttribute()
	{
		foreach (self::roleList() as $role => $title) {
			if ($role == $this->role)
				return $title;
		}
		return false;
  }

  /**
   * Атрибут Фамилия Инициалы
   */
  public function getInitialsAttribute() {
    $initials = null;
    $initials = ($this->first_name) ? $this->first_name : $initials;
    $initials = ($initials && $this->last_name) ? ($initials . ' ' . mb_substr($this->last_name, 0, 1).'.') : $initials;
    return $initials;
  }

  /**
   * Атрибут Первые бквы Фамилиии и Имени
   */
  public function getLiteraAttribute() {
    $litera = null;
    $litera = ($this->last_name) ? mb_substr($this->last_name, 0, 1) : $litera;
    $litera = ($this->first_name)  ? $litera . ' ' .mb_substr($this->first_name, 0, 1) : $litera;
    return $litera;
  }

  /**
   * Полное ФИО
   */
  public function getFioFullAttribute() {
    $fio = null;
    $fio = ($this->last_name) ? $this->last_name : $fio;
    $fio = ($this->first_name) ? ($fio . ' ' . $this->first_name) : $fio;
    $fio = ($this->patronymic) ? ($fio . ' ' . $this->patronymic) : $fio;
    return $fio;
  }

  /**
   * Полное ФИ
   */
  public function getFlNameAttribute() {
    $fio = null;
    $fio = ($this->last_name) ? $this->last_name : $fio;
    $fio = ($fio && $this->first_name) ? ($fio . ' ' . $this->first_name) : $fio;
    return $fio;
  }

  /**
   * Атрибут хеш пользователя
   */
	public function getHashAttribute()
	{
    $string = $this->role . $this->id .  substr($this->created_at, -2);
		return md5($string);
  }

  /**
	 * Является ли пользователь администратором
	 */
	public function isAdmin(): bool
	{
		return $this->role === Role::ADMIN;
	}

  /**
   * Является ли пользователь модератором платформы
   */
  public function isManager(): bool
  {
    return $this->role === Role::MANAGER;
  }

  /**
   * Generate password
   */
  public static function generatePassword()
	{
    $string = ['a','b','c','d','e','f','g','h','k','n','p','r','s','t','v','w','x','y','z'];
    $max = count($string) - 1;

    return  rand(0,9) . $string[rand(0,$max)] .
            rand(0,9) . $string[rand(0,$max)] .
            rand(0,9) . $string[rand(0,$max)] .
            rand(0,9) . $string[rand(0,$max)];
  }

  /**
   * Reviews
   */
  public function Reviews(): HasMany
  {
    return $this->hasMany(Review::class, 'recipient_id', 'id');
  }

  /**
   * ReviewPosts
   */
  public function ReviewPosts(): HasMany
  {
    return $this->hasMany(Review::class, 'author_id', 'id');
  }
}
