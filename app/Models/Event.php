<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
      'start_date' => 'date',
      'end_date' => 'date',
    ];
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
      ? asset('storage/' . ltrim($relativePath, '/'))
      : null;
  }

  public function registrations(): HasMany
  {
    return $this->hasMany(Registration::class, 'event_id', 'event_id');
  }
}
