<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationQuestion extends Model
{
    protected $table = 'evaluation_questions';

    protected $primaryKey = 'question_id';

    protected $fillable = [
        'event_id',
        'question_text',
        'question_type',
        'sort_order',
        'is_required',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EvaluationAnswer::class, 'question_id', 'question_id');
    }
}
