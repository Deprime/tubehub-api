<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseStatus extends Model
{
  protected $table = 'course_status';

  public const DRAFT_ID = 1;
  public const PUBLISHED_ID = 2;
}
