<?php

namespace Tests\Traits;

use App\Models\Tag;

trait TagTrait
{
    protected $tag;

    public function createTag(): Tag
    {
        return $this->tag = Tag::factory()->create();
    }
}
