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
    'description',
    'location',
    'banner_image',
    'event_date',
  ];

  protected function casts(): array
  {
    return [
      'event_date' => 'date',
    ];
  }

  public function getBannerUrlAttribute(): ?string
  {
    if (! $this->banner_image) {
      return null;
    }

    if (filter_var($this->banner_image, FILTER_VALIDATE_URL)) {
      return $this->banner_image;
    }

    return Storage::disk('public')->exists($this->banner_image)
      ? '/storage/' . ltrim($this->banner_image, '/')
      : null;
  }

  public function registrations(): HasMany
  {
    return $this->hasMany(Registration::class, 'event_id', 'event_id');
  }
}
