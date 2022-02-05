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

class Subs extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'subs';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'language_id',
    'author_id',
    'lesson_id',
    'video_id',
    'file_name',
    'url',
  ];


  /**
   * Create rules
   * TODO: Available video formats validation
   * @var array<string, string>
   */
  protected static function create_rules()
  {
    return [
      'language_id' => 'required|integer',
      'author_id'  => 'required|integer',
      'lesson_id'  => 'required|integer',
      'video_id'   => 'required|integer',
      'file' => [
        'required',
        'mimes:vvt',
      ],
    ];
  }

  /**
   * Language
   */
  public function language(): BelongsTo
  {
    return $this->belongsTo(Language::class, 'language_id');
  }

  /**
   * Author
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'author_id');
  }

  /**
   * Lesson
   */
  public function lesson(): BelongsTo
  {
    return $this->belongsTo(Lesson::class, 'lesson_id');
  }
}
