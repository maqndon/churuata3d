<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'url'
    ];

    public function siteSettingsSocialMedia(): HasMany
    {
        return $this->hasMany(SiteSettingSocialMedia::class);
    }
}
