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

class Course extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'course';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'author_id',
    'label',
    'title',
    'description',
    'preview_url',
    'price',
    'status_id',
    'published_at',
  ];


  /**
   * Create rules
   *
   * @return array
   */
  protected static function create_rules()
  {
    return [
      'author_id'    => 'required|integer',
      'label'        => 'required|string|min:2|max:20',
      'title'        => 'required|string|min:2|max:255',
      'description'  => 'nullable|string|min:10',
      'preview_url'  => 'nullable|string',
      'price'        => 'required|numeric',
      'published_at' => 'nullable|datetime',
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
      'author_id'    => 'required|integer',
      'label'        => 'required|string|min:2|max:20',
      'title'        => 'required|string|min:2|max:255',
      'description'  => 'nullable|string|min:10',
      'preview_url'  => 'nullable|string',
      'price'        => 'required|numeric',
      'published_at' => 'nullable|datetime',
    ];
  }

  /**
   * Author
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'author_id');
  }

  /**
   * Status
   */
  public function status(): BelongsTo
  {
    return $this->belongsTo(CourseStatus::class, 'status_id');
  }

  /**
   * Chapters
   */
  public function chapters(): HasMany
  {
    return $this->hasMany(Chapter::class, 'course_id', 'id');
  }

  /**
   * Lessons
   */
  public function lessons(): HasMany
  {
    return $this->hasMany(Lesson::class, 'course_id', 'id');
  }
}
