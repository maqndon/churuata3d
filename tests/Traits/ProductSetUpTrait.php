<?php

namespace Tests\Traits;

use Database\Seeders\ChuruataDatabaseSeeder;

trait ProductSetUpTrait
{
    use ProductTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->seed(ChuruataDatabaseSeeder::class);
        
        $this->product = $this->createProduct();

    }
}
