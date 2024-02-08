<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'site_title',
        'site_description',
        'site_logo',
        'site_google_analytics_tracking_id',
    ];

    public function social_media(): BelongsToMany
    {
        return $this->belongsToMany(SocialMedia::class)
            ->withPivot('id', 'url', 'social_media_id', 'site_setting_id')
            ->withTimestamps();
    }

}
