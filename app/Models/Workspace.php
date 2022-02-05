<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Workspace extends Model
{
  use SoftDeletes;

  const CUSTOM_DATE_FORMAT = 'd.m.Y';
  protected $table = 'workspace';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'owner_id',
    'label',
    'title',
    'description',
    'poster_url',
  ];


  /**
   * Create rules
   *
   * @return array
   */
  protected static function create_rules()
  {
    return [
      'owner_id'    => 'required|integer',
      'label'       => 'required|string|min:2|max:20',
      'title'       => 'required|string|min:2|max:255',
      'description' => 'nullable|string|min:10',
      'poster_url'  => 'nullable|string',
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
      'label'       => 'required|string|min:2|max:20',
      'title'       => 'required|string|min:2|max:255',
      'description' => 'nullable|string|min:10',
      'poster_url'  => 'nullable|string',
    ];
  }
}
