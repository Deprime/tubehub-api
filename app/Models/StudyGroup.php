<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\{
  HasMany,
};

class StudyGroup extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'study_group';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'title',
  ];


  /**
   * Create rules
   *
   * @return array
   */
  protected static function create_rules()
  {
    return [
      // 'author_id'       => 'required|integer',
      'title' => 'required|string|min:2|max:255',
    ];
  }

  /**
   * Replies
   */
  public function replies(): HasMany
  {
    return $this->hasMany(Student::class, 'study_group_id', 'id');
  }
}
