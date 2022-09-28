<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Request;

class userService
{

    public function createUser(Request $request): User
    {
        $user = User::create([
            'first_name' => $request->first_name, 
            'last_name' => $request->last_name, 
            'username' => $request->username,
            'email' => $request->email, 
            'role_id' => $request->role,
            'password' => Hash::make('password'),
        ]);


        return $user;
    } 

}