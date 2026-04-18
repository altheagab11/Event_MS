<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationVerificationCode extends Model
{
  protected $fillable = [
    'event_id',
    'email',
    'verification_code_hash',
    'payload',
    'paper_temp_path',
    'status',
    'attempts',
    'expires_at',
    'verified_at',
  ];

  protected function casts(): array
  {
    return [
      'payload' => 'array',
      'expires_at' => 'datetime',
      'verified_at' => 'datetime',
    ];
  }
}
