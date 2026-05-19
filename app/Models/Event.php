<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    protected $table = 'events';

    protected $primaryKey = 'event_id';

    public $timestamps = false;

    protected $fillable = [
        'event_name',
        'hosted_by',
        'event_type',
        'attendance_format',
        'description',
        'location',
        'banner_image',
        'event_date',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function getFormattedScheduleRangeAttribute(): string
    {
        $start = $this->start_date ?? $this->event_date;
        if ($start === null) {
            return 'Date TBA';
        }

        $start = $start instanceof Carbon ? $start : Carbon::parse((string) $start);
        $end = $this->end_date ?? $start;
        $end = $end instanceof Carbon ? $end : Carbon::parse((string) $end);

        if (! $this->start_date && $this->event_date && ! $this->end_date) {
            return $start->format('F j, Y');
        }

        if ($start->toDateString() !== $end->toDateString()) {
            return $start->format('F j, Y g:i A').' - '.$end->format('F j, Y g:i A');
        }

        return $start->format('F j, Y g:i A').' - '.$end->format('g:i A');
    }

    public function getBannerUrlAttribute(): ?string
    {
        if (! $this->banner_image) {
            return null;
        }

        $bannerImage = (string) $this->banner_image;

        if (filter_var($bannerImage, FILTER_VALIDATE_URL)) {
            return $bannerImage;
        }

        $relativePath = ltrim($bannerImage, '/');
        $relativePath = preg_replace('#^storage/#', '', $relativePath) ?? $relativePath;
        $relativePath = preg_replace('#^public/#', '', $relativePath) ?? $relativePath;

        return Storage::disk('public')->exists($relativePath)
          ? asset('storage/'.ltrim($relativePath, '/'))
          : null;
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'event_id', 'event_id');
    }

    public function eventRegistrants(): HasMany
    {
        return $this->hasMany(EventRegistrant::class, 'event_id', 'event_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(EventSession::class, 'event_id', 'event_id')
            ->orderBy('session_date');
    }

    /**
     * @return array{label: string, state: string, can_register: bool}
     */
    public function publicRegistrationCardState(?Carbon $now = null): array
    {
        $now = $now ?? now();

        $start = $this->start_date ?? $this->event_date;
        if ($start !== null && ! $start instanceof Carbon) {
            $start = Carbon::parse((string) $start);
        }

        $end = $this->end_date ?? $this->event_date;
        if ($end !== null && ! $end instanceof Carbon) {
            $end = Carbon::parse((string) $end);
        }
        if ($end instanceof Carbon && ! $this->end_date && $this->event_date) {
            $end = $end->copy()->endOfDay();
        }

        if ($start instanceof Carbon && $now->lt($start)) {
            return [
                'label' => 'Register Now',
                'state' => 'register',
                'can_register' => true,
            ];
        }

        if ($end instanceof Carbon && $now->lt($end)) {
            return [
                'label' => 'Registration Closed',
                'state' => 'closed',
                'can_register' => false,
            ];
        }

        return [
            'label' => 'Event Completed',
            'state' => 'completed',
            'can_register' => false,
        ];
    }
}
