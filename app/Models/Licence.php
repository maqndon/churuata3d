<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licence extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'link', 'icon', 'logo'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
