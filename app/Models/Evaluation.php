<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
  protected $table = 'evaluations';

  protected $primaryKey = 'evaluation_id';

  public $timestamps = false;

  protected $fillable = [
    'paper_id',
    'evaluator_id',
    'registration_id',
    'event_id',
    'participant_email',
    'score',
    'comment',
    'evaluated_at',
  ];

  protected function casts(): array
  {
    return [
      'score' => 'float',
      'evaluated_at' => 'datetime',
    ];
  }
}
