<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\{
  BelongsTo,
};

class Student extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'student';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'author_id',
    'study_group_id',
    'first_name',
    'last_name',
    'email',
  ];


  /**
   * Create rules
   *
   * @return array
   */
  protected static function create_rules()
  {
    return [
      'author_id'       => 'required|integer',
      'study_group_id'  => 'required|integer|between:1,5',
      'first_name'      => 'required|string|min:2|max:255',
      'last_name'       => 'required|string|min:2|max:255',
      'email'           => 'required|string|email:rfc,strict,filter',
    ];
  }

  /**
   * Update rules
   *
   * @return array
   */
  protected static function update_rules()
  {
    return [
      'study_group_id'  => 'required|integer|between:1,5',
      'first_name'      => 'required|string|min:2|max:255',
      'last_name'       => 'required|string|min:2|max:255',
      'email'           => 'required|string|email:rfc,strict,filter',
    ];
  }

  /**
   * Group
   */
  public function study_group(): BelongsTo
  {
    return $this->belongsTo(StudyGroup::class, 'study_group_id');
  }

  /**
   * Author
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'author_id');
  }
}
