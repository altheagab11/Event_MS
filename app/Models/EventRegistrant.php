<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventRegistrant extends Model
{
    protected $table = 'event_registrants';

    protected $primaryKey = 'event_registrant_id';

    protected $fillable = [
        'event_id',
        'first_name',
        'last_name',
        'email',
        'school_university',
        'user_type',
        'participant_role',
        'status',
        'registration_date',
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'event_registrant_id', 'event_registrant_id');
    }
}
