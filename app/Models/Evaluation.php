<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'registration_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EvaluationAnswer::class, 'evaluation_id', 'evaluation_id');
    }
}
