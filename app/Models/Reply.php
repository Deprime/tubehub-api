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

class Reply extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'reply';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'author_id',
    'task_id',
    'content',
    'approved_at',
  ];

  protected $hidden = [];


  /**
   * Create rules
   *
   * @return array
   */
  protected static function create_rules()
  {
    return [
      'author_id'    => 'required|integer',
      'recipient_id' => 'required|integer',
      'content'      => 'required|string|min:10',
      'mark'         => 'required|numeric|min:1|max:5',
    ];
  }

  public function scopeApproved(EloquentBuilder $query): EloquentBuilder
  {
    return $query->whereNotNull('approved_at', true);
  }

  /**
   * Author
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(Author::class, 'author_id');
  }

  /**
   * Task
   */
  public function task(): BelongsTo
  {
    return $this->belongsTo(Task::class, 'task_id');
  }
}
