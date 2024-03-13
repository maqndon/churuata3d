<?php

namespace Tests\Traits;

use App\Models\Category;

trait CategoryTrait
{
    protected $category;

    public function createCategory(): Category
    {
        return $this->category = Category::factory()->create();
    }
}
