<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DownloadProductZipFileTest extends TestCase
{
    /** @test */
    public function example(): void
    {
        $response = $this->get('/');

        $response-> assertOk();
    }
}
