<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designations = Designation::with('department')->paginate(10);
        return view('designations.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('designations.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'dept_id' => 'required|exists:departments,id',
            'description' => 'required|string|max:255'
        ]);

        Designation::create($validatedData);

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $designation = Designation::with('department', 'positions')->findOrFail($id);
        return view('designations.show', compact('designation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $designation = Designation::findOrFail($id);
        $departments = Department::all();
        return view('designations.edit', compact('designation', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'dept_id' => 'required|exists:departments,id',
            'description' => 'required|string|max:255'
        ]);

        $designation = Designation::findOrFail($id);
        $designation->update($validatedData);

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}

