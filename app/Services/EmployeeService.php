<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;

class employeeService
{

    public function createEmployee(Request $request): Employee
    {
        $employee = Employee::create([
            'first_name' => $request->first_name, 
            'last_name' => $request->last_name, 
            'company_id' => $request->company_id,
            'email' => $request->email, 
            'phone' => $request->phone,
        ]);

        return $employee;
    } 

}