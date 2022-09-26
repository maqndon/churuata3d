<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //search query
        $actualQuery = Request::all('search');

        return Inertia::render('Users/Index', [
            'users' => User::query()
                ->join('roles', 'role_id', '=', 'roles.id')
                ->select('users.id', 'users.name', 'users.email', 'roles.name as role')
                ->when($actualQuery['search'], function ($query, $search) {
                    $query->where('users.name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString(),
            'query' => [
                'filters' => $actualQuery['search'],
                'table' => 'users'
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Inertia::render('users.show', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $data = [
            'user' => $user,
            'roles' => Role::all(),
            'table' => 'roles',
        ];

        // $user = new UserResource($user);
        return Inertia::render('Users/Edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        $user->name = Request::json('username');
        $user->email = Request::json('email');
        $user->role_id = Request::json('role');

        $user->update();

        // return redirect()->route('Users/Index', $user)->with('status', 'The user was successfully updated');
        return Redirect::route('users.edit', $user, 302, ['The user was successfully updated']);

        // $user->update(
        // Request::validate([
        //         $user->name => ['required', 'max:50'],
        //         $user->email => ['required', 'max:50', 'email'],
        //         $user->role_id => ['required', 'max:2']
        //     ])
        // );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
