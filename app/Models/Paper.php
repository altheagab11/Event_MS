<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
  protected $table = 'papers';

  protected $primaryKey = 'paper_id';

  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'event_id',
    'title',
    'file_path',
    'status',
    'created_at',
  ];

  protected function casts(): array
  {
    return [
      'created_at' => 'datetime',
    ];
  }
}
