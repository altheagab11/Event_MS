<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
  protected $table = 'registrations';

  protected $primaryKey = 'registration_id';

  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'event_id',
    'registration_date',
    'status',
  ];

  protected function casts(): array
  {
    return [
      'registration_date' => 'datetime',
    ];
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function event(): BelongsTo
  {
    return $this->belongsTo(Event::class, 'event_id', 'event_id');
  }

  public function attendance(): HasOne
  {
    return $this->hasOne(Attendance::class, 'registration_id', 'registration_id');
  }
}
