<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PrintSetting extends Model
{
    use HasFactory;

    protected $fillable = ['printing_material_id', 'print_strength', 'resolution', 'infill', 'top_layers', 'bottom_layers', 'walls', 'speed'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_print_settings');
    }
}
