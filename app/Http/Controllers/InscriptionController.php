<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Inscription::all(), 200);
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
            'date_inscription' => 'required|date',
            'status' => 'required|string',
            'price' => 'required|numeric',
            'formation_id' => 'required|exists:formations,id',
            'user_id' => 'required|exists:users,id', // Change to refer to user_id instead of etudiant_id
        ]);

        $inscription = Inscription::create($validatedData);

        return response()->json($inscription, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inscription = Inscription::findOrFail($id);
        return response()->json($inscription, 200);
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
        $inscription = Inscription::findOrFail($id);
        $inscription->update($request->all());
        return response()->json($inscription, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inscription = Inscription::findOrFail($id);
        $inscription->delete();
        return response()->json(null, 204);
    }
}
