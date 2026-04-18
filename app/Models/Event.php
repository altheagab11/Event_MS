<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  protected $table = 'events';

  protected $primaryKey = 'event_id';

  public $timestamps = false;

  protected $fillable = [
    'event_name',
    'description',
    'location',
    'banner_image',
    'event_date',
  ];

  protected function casts(): array
  {
    return [
      'event_date' => 'date',
    ];
  }
}
