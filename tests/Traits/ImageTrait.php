<?php

namespace Tests\Traits;

use App\Models\Image;

trait ImageTrait
{
    protected $image;

    public function makeImage(): Image
    {
        return $this->image = Image::factory()->make();
    }
}
