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

class Task extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'task';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'author_id',
    'title',
    'description',
    'requirements',
    'form_url',
    'price',
    'time_limit',
    'status_id',
    'type_id',
    'periodicity_id',
    'published_at',
    'finished_at',
  ];

  protected $protected_fillable = [
    'author_id',
    'status_id',
    'published_at',
    'finished_at',
  ];

  protected $hidden = [];

  /**
   * Get fields fillable by user
   */
  protected static function getFillableByUser() {
    return array_filter(self::$fillable, function($field) {
      return !in_array($field, self::$protected_fillable);
    });
  }


  /**
   * Create rules
   *
   * @return array
   */
  protected static function create_rules()
  {
    return [
      'author_id'    => 'required|integer',
      'title'        => 'required|string|min:5',
      'description'  => 'required|string|min:5',
      'requirements' => 'nullable|string|min:5',
      'form_url'     => 'nullable|url',
      'price'        => 'required|numeric',
      'time_limit'   => 'required|numeric',

      'status_id'      => 'required|integer',
      'type_id'        => 'required|integer',
      'periodicity_id' => 'required|integer',
    ];
  }

  public function scopeUnpublished(EloquentBuilder $query): EloquentBuilder
  {
    return $query->whereNull('published_at', true)->where('status_id', 1);
  }

  /**
   * Author
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(Author::class, 'author_id');
  }

  /**
   * Status
   */
  public function status(): BelongsTo
  {
    return $this->belongsTo(TaskStatus::class, 'status_id');
  }

  /**
   * Type
   */
  public function type(): BelongsTo
  {
    return $this->belongsTo(TaskType::class, 'type_id');
  }

  /**
   * Periodicity
   */
  public function periodicity(): BelongsTo
  {
    return $this->belongsTo(TaskPeriodicity::class, 'periodicity_id');
  }

  /**
   * Replies
   */
  public function replies(): HasMany
  {
    return $this->hasMany(Reply::class, 'task_id', 'id');
  }

  /**
   * Approved reply
   */
  public function approvedReply(): HasMany
  {
    return $this->HasOne(Reply::class, 'task_id', 'id')->approved();
  }
}
