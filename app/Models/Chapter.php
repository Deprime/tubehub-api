<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\{
  HasMany,
  HasOne,
  BelongsTo,
  BelongsToMany,
};

class Chapter extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'chapter';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'course_id',
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
      'course_id'       => 'required|integer',
      'title'           => 'required|string|min:2|max:255',
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
      'course_id'       => 'required|integer',
      'title'           => 'required|string|min:2|max:255',
    ];
  }

  /**
   * Lessons
   */
  public function lessons(): HasMany
  {
    return $this->hasMany(Lesson::class, 'chapter_id', 'id');
  }
}
