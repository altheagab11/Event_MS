<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationAnswer extends Model
{
    protected $table = 'evaluation_answers';

    protected $primaryKey = 'answer_id';

    public $timestamps = false;

    protected $fillable = [
        'evaluation_id',
        'question_id',
        'rating_value',
        'answer_text',
    ];

    protected function casts(): array
    {
        return [
            'rating_value' => 'integer',
        ];
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id', 'evaluation_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(EvaluationQuestion::class, 'question_id', 'question_id');
    }
}
