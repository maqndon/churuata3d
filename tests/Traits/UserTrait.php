<?php

namespace Tests\Traits;

use App\Models\User;

trait UserTrait
{
    protected $user;

    public function createUser(): User
    {
        return $this->user = User::factory()->create();
    }
}
