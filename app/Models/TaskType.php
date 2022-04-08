<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{
  HasMany,
};

class TaskType extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'task_type';

  protected $hidden = [
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  /**
   * Tasks
   */
  public function tasks(): HasMany
  {
    return $this->hasMany(Task::class, 'type_id', 'id');
  }
}
