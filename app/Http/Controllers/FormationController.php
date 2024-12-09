<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Formation::with('category')->get(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'duration' => 'required|integer', // Expecting an integer for duration
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id', // Ensure category exists in categories table
        ]);

        try {
            $formation = Formation::create($validatedData);
            return response()->json($formation, 201);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Formation creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $formation = Formation::with('category')->findOrFail($id);
        return response()->json($formation, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formation = Formation::findOrFail($id);
        $formation->update($request->all());
        return response()->json($formation, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $formation = Formation::findOrFail($id);
        $formation->delete();
        return response()->json(null, 204);
    }
}
