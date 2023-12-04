<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;

class companyService
{

    public function createCompany(Request $request): Company
    {
        $company = Company::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'website' => $request->website,
            'logo' => $request->logo, 
        ]);

        return $company;
    } 

}