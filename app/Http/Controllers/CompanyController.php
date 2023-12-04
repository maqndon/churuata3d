<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Companies\StoreCompanyRequest;

class CompanyController extends Controller
{
    public function index()
    {

        //search query
        $actualQuery = Request::all('search');

        return Inertia::render('Companies/Index', [
            'companies' => Company::query()
                ->select('companies.id', 'companies.name', 'companies.email', 'companies.website', 'companies.logo')
                ->when($actualQuery['search'], function ($query, $search) {
                    $query->where('companies.name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString(),
            'query' => [
                'filters' => $actualQuery['search'],
                'table' => 'companies'
            ],
            'can' => [
                'createCompany' => Auth::user()->can('create', Company::class)
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
        return Inertia::render('Companies/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request, CompanyService $companyService)
    {

        $company = $companyService->createCompany($request);

        return redirect()->route('companies.store', $company)
            ->with('message', 'The Company was successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Inertia::render('companies.show', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $data = [
            'company' => $company,
            'roles' => Role::all(),
            'table' => 'roles',
        ];

        return Inertia::render('Companies/Edit', [
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
    public function update(Company $company)
    {
        $company->name = Request::json('name');
        $company->email = Request::json('email');
        $company->website = Request::json('website');
        $company->logo = Request::json('logo');

        Request::validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email'],
            'website' => ['required', 'max:50'],
            'logo' => ['nullable', 'max:5'],
        ]);

        $company->update();

        return redirect()->route('companies.edit', $company)->with('message', 'The Company was successfully updated');
    }
}
