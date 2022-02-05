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

class Review extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'review';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'author_id',
    'recipient_id',
    'content',
    'mark',
  ];

  protected $hidden = [
    'updated_at',
    'deleted_at',
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
      'recipient_id' => 'required|integer',
      'content'      => 'required|string|min:10',
      'mark'         => 'required|numeric|min:1|max:5',
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
      'recipient_id' => 'required|integer',
      'content'      => 'required|string|min:10',
      'mark'         => 'required|numeric|min:1|max:5',
    ];
  }

  /**
   * Author
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(Author::class, 'author_id');
  }

  /**
   * Recipient
   */
  public function recipient(): BelongsTo
  {
    return $this->belongsTo(User::class, 'recipient_id');
  }
}
