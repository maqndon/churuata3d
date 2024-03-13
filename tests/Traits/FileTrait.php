<?php

namespace Tests\Traits;

use App\Models\File;

trait FileTrait
{
    protected $file;

    public function makeFile(): File
    {
        return $this->file = File::factory()->make();
    }
}
