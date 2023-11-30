<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Users\StoreUserRequest;

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
                ->select('users.first_name', 'users.last_name', 'users.id', 'users.username', 'users.email', 'roles.name as role')
                ->when($actualQuery['search'], function ($query, $search) {
                    $query->where('users.first_name', 'like', "%{$search}%")
                        ->orWhere('users.last_name', 'like', "%{$search}%")
                        ->orWhere('users.username', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString(),
            'query' => [
                'filters' => $actualQuery['search'],
                'table' => 'users'
            ],
            'can' => [
                'createUser' => Auth::user()->can('create', User::class)
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
        $data = [
            'roles' => Role::all(),
            'table' => 'roles',
        ];

        // $user = new UserResource($user);
        return Inertia::render('Users/Create', [
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request, UserService $userService)
    {

        $user = $userService->createUser($request);

        return redirect()->route('users.store', $user)
            ->with('message', 'The user was successfully created');
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
        $user->first_name = Request::json('first_name');
        $user->last_name = Request::json('last_name');
        $user->username = Request::json('username');
        $user->email = Request::json('email');
        $user->role_id = Request::json('role');

        Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'username' => ['required', 'max:50'],
                'email' => ['required', 'max:50', 'email'],
                'role' => ['required', 'max:2']
        ]);

        $user->update();

        return redirect()->route('users.edit', $user)->with('message', 'The user was successfully updated');

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
