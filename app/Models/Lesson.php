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

class Lesson extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'lesson';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'course_id',
    'chapter_id',
    'status_id',
    'title',
    'description',
    'content_type',
    'is_guest_access',
    'is_free',
    'preview_url',
    'sort',
  ];


    /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'is_free'         => 'boolean',
    'is_guest_access' => 'boolean',
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
      'chapter_id'      => 'nullable|integer',
      'title'           => 'required|string|min:2|max:255',
      'description'     => 'nullable|string|min:10',
      'is_guest_access' => 'required|boolean',
      'is_free'         => 'required|boolean',
      'preview_url'     => 'nullable|string',
      'sort_index'      => 'required|integer',
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
      'chapter_id'      => 'nullable|integer',
      'title'           => 'required|string|min:2|max:255',
      'description'     => 'nullable|string|min:10',
      'is_guest_access' => 'required|boolean',
      'is_free'         => 'required|boolean',
      'preview_url'     => 'nullable|string',
      'sort_index'      => 'required|integer',
    ];
  }

  /**
   * Course
   */
  public function course(): BelongsTo
  {
    return $this->belongsTo(Course::class, 'course_id');
  }

  /**
   * Chapter
   */
  public function chapter(): BelongsTo
  {
    return $this->belongsTo(Chapter::class, 'chapter_id');
  }

  /**
   * Video
   */
  public function video(): HasOne
  {
    return $this->hasOne(Video::class, 'lesson_id');
  }

  /**
   * Status
   */
  public function status(): BelongsTo
  {
    return $this->belongsTo(LessonStatus::class, 'status_id');
  }
}
