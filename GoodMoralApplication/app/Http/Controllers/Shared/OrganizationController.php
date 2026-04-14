<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;

use App\Models\Organization;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrganizationRequest;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Organization::with('department');

        if ($request->filled('search_name')) {
            $query->where('description', 'LIKE', '%' . $request->search_name . '%');
        }
        if ($request->filled('search_department')) {
            if ($request->search_department === 'none') {
                $query->whereNull('department_id');
            } else {
                $query->whereHas('department', function ($q) use ($request) {
                    $q->where('department_code', $request->search_department);
                });
            }
        }

        $organizations = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());
        $departments = Department::orderBy('department_code')->whereNotIn('department_code', ['SOM', 'GRADSCH'])->get();
        return view('organizations.index', compact('organizations', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('department_code')->whereNotIn('department_code', ['SOM', 'GRADSCH'])->get();
        return view('organizations.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationRequest $request)
    {
        $validatedData = $request->validated();

        Organization::create($validatedData);

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $organization = Organization::with('department', 'positions')->findOrFail($id);
        return view('organizations.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $organization = Organization::findOrFail($id);
        $departments = Department::orderBy('department_code')->whereNotIn('department_code', ['SOM', 'GRADSCH'])->get();
        return view('organizations.edit', compact('organization', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOrganizationRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $organization = Organization::findOrFail($id);
        $organization->update($validatedData);

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }
}
