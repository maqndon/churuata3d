<?php

namespace Tests\Traits;

use App\Models\Product;
use Database\Seeders\ChuruataDatabaseSeeder;

trait ProductPrepareTrait
{
    use ProductTrait;

    public function prepareProduct(): Product
    {
        $this->seed();
        $this->seed(ChuruataDatabaseSeeder::class);

        return $this->createProduct();
    }
}
