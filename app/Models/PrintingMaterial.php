<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PrintingMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'nozzle_size', 'min_hot_bed_temp', 'max_hot_bed_temp'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(PrintSetting::class);
    }
}
