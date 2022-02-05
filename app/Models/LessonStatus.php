<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonStatus extends Model
{
  protected $table = 'lesson_status';

  public const DRAFT_ID = 1;
  public const PUBLISHED_ID = 3;
}
