<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;

use App\Models\Position;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\StorePositionRequest;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = Position::with('organization.department')->paginate(10);
        return view('positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizations = Organization::with('department')->get();
        return view('positions.create', compact('organizations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePositionRequest $request)
    {
        $validatedData = $request->validated();

        Position::create($validatedData);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $position = Position::with('organization.department')->findOrFail($id);
        return view('positions.show', compact('position'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $position = Position::findOrFail($id);
        $organizations = Organization::with('department')->get();
        return view('positions.edit', compact('position', 'organizations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePositionRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $position = Position::findOrFail($id);
        $position->update($validatedData);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position deleted successfully.');
    }
}

