<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            'photo' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->hasFile('photo')) {
                        // Valider si c'est un fichier image
                        $file = $request->file('photo');
                        if (!$file->isValid() || !in_array($file->extension(), ['jpeg', 'png', 'jpg', 'gif'])) {
                            $fail('The ' . $attribute . ' must be a valid image of type: jpeg, png, jpg, gif.');
                        }
                    } elseif (!filter_var($value, FILTER_VALIDATE_URL)) {
                        // Valider si c'est une URL valide
                        $fail('The ' . $attribute . ' must be a valid URL or an image.');
                    }
                }
            ],
            'videoId' => 'nullable|string'
        ]);

        // Gérer l'upload de l'image
        $photoPath = null;

        if ($request->hasFile('photo')) {
            // Stocker le fichier téléversé
            $photoPath = $request->file('photo')->store('formations', 'public');
        } elseif ($request->photo) {
            // Prendre directement l'URL
            $photoPath = $request->photo;
        }

        try {
            $formation = Formation::create(array_merge($validatedData, ['photo' => $photoPath]));
            return response()->json($formation, 201);
        } catch (\Exception $e) {
            Log::error('Formation creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $formation = Formation::with('category', 'comments')->findOrFail($id);
        // Ajouter l'URL complète pour l'image si elle existe
        if ($formation->photo) {
            // Si la photo est un fichier local
            if (!filter_var($formation->photo, FILTER_VALIDATE_URL)) {
                $formation->photo = asset('storage/' . $formation->photo);
            }
        }
        return response()->json([
            'course' => $formation,
            'comments' => $formation->comments
        ], 200);
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

        $validatedData = $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'photo' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->hasFile('photo')) {
                        // Valider si c'est un fichier image
                        $file = $request->file('photo');
                        if (!$file->isValid() || !in_array($file->extension(), ['jpeg', 'png', 'jpg', 'gif'])) {
                            $fail('The ' . $attribute . ' must be a valid image of type: jpeg, png, jpg, gif.');
                        }
                    } elseif (!filter_var($value, FILTER_VALIDATE_URL)) {
                        // Valider si c'est une URL valide
                        $fail('The ' . $attribute . ' must be a valid URL or an image.');
                    }
                }
            ],
            'videoId' => 'nullable|string'
        ]);

        // Si une nouvelle photo est envoyée
        // Gérer l'upload de l'image
        $photoPath = null;

        if ($request->hasFile('photo')) {
            // Stocker le fichier téléversé
            $photoPath = $request->file('photo')->store('categories', 'public');
        } elseif ($request->photo) {
            // Prendre directement l'URL
            $photoPath = $request->photo;
        }

        // Mise à jour de la formation
        $formation->update(array_merge($validatedData, ['photo' => $photoPath]));

        return response()->json($formation, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $formation = Formation::findOrFail($id);
        // Vérifier si la photo est un fichier local
        if ($formation->photo && !filter_var($formation->photo, FILTER_VALIDATE_URL)) {
            // Supprimer le fichier de stockage
            Storage::disk('public')->delete($formation->photo);
        }
        // Supprimer la formation
        $formation->delete();
        return response()->json(null, 204);
    }

}
