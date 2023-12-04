<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Employees\StoreEmployeeRequest;

class EmployeeController extends Controller
{

    public function index()
    {

        //search query
        $actualQuery = Request::all('search');

        return Inertia::render('Employees/Index', [
            'employees' => Employee::query()
                ->join('companies', 'employees.company_id', '=', 'companies.id')
                ->select('employees.first_name', 'employees.last_name', 'employees.id', 'employees.email', 'employees.phone', 'companies.name as company')
                ->when($actualQuery['search'], function ($query, $search) {
                    $query->where('employees.first_name', 'like', "%{$search}%")
                        ->orWhere('employees.last_name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString(),
            'query' => [
                'filters' => $actualQuery['search'],
                'table' => 'employees'
            ],
            'can' => [
                'createEmployee' => Auth::user()->can('create', Employee::class)
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
            'companies' => Company::all(),
        ];

        return Inertia::render('Employees/Create', [
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request, EmployeeService $employeeService)
    {

        $employee = $employeeService->createEmployee($request);

        return redirect()->route('employees.store', $employee)
            ->with('message', 'The employee was successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Inertia::render('employees.show', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $data = [
            'employee' => $employee,
            'company' => Company::select('name')->where('id', '=', $employee->company_id)->first(),
            'roles' => Role::all(),
            'table' => 'roles',
        ];

        return Inertia::render('Employees/Edit', [
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
    public function update(Employee $employee)
    {
        $employee->first_name = Request::json('first_name');
        $employee->last_name = Request::json('last_name');
        $employee->company_id = Request::json('company_id');
        $employee->email = Request::json('email');
        $employee->phone = Request::json('phone');

        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'company_id' => ['required', 'max:5'],
            'email' => ['required', 'max:50', 'email'],
            'phone' => ['required', 'max:50']
        ]);

        $employee->update();

        return redirect()->route('employees.edit', $employee)->with('message', 'The employee was successfully updated');
    }
}
