<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
  protected $table = 'attendance';

  protected $primaryKey = 'attendance_id';

  public $timestamps = false;

  protected $fillable = [
    'registration_id',
    'check_in_time',
    'check_out_time',
  ];

  protected function casts(): array
  {
    return [
      'check_in_time' => 'datetime',
      'check_out_time' => 'datetime',
    ];
  }

  public function registration(): BelongsTo
  {
    return $this->belongsTo(Registration::class, 'registration_id', 'registration_id');
  }
}
