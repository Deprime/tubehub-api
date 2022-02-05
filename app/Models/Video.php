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

class Video extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'video';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'course_id',
    'lesson_id',
    'cdn_url',
    'file_name',
    'extension',
    'metadata',
  ];


    /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'metadata' => 'object',
  ];

  // TODO: Available video formats validation

  /**
   * Course
   */
  public function course(): BelongsTo
  {
    return $this->belongsTo(Course::class, 'course_id');
  }

  /**
   * Lesson
   */
  public function lesson(): BelongsTo
  {
    return $this->belongsTo(Lesson::class, 'lesson_id');
  }

  /**
   * Subs
   */
  public function subs(): HasMany
  {
    return $this->hasMany(Subs::class, 'lesson_id', 'id');
  }
}
