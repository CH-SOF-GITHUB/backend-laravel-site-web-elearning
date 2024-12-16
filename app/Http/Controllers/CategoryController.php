<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Category::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string',
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
        ]);

        // Gérer l'upload de l'image
        $photoPath = null;

        if ($request->hasFile('photo')) {
            // Stocker le fichier téléversé
            $photoPath = $request->file('photo')->store('categories', 'public');
        } elseif ($request->photo) {
            // Prendre directement l'URL
            $photoPath = $request->photo;
        }

        $category = Category::create(array_merge($validatedData, ['photo' => $photoPath]));

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);

        // Vérifier si 'photo' contient une valeur et est un chemin de fichier local ou une URL
        if ($category->photo) {
            if (!filter_var($category->photo, FILTER_VALIDATE_URL)) {
                // Si c'est un chemin local, générer l'URL complète pour l'accès
                $category->photo = asset('storage/' . $category->photo);
            }
        }

        return response()->json($category, 200);
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
        $category = Category::findOrFail($id);
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string',
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
        ]);

        // Gérer l'upload de l'image
        $photoPath = null;

        if ($request->hasFile('photo')) {
            // Stocker le fichier téléversé
            $photoPath = $request->file('photo')->store('categories', 'public');
        } elseif ($request->photo) {
            // Prendre directement l'URL
            $photoPath = $request->photo;
        }

        $category->update(array_merge($validatedData, ['photo' => $photoPath]));
        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        // Supprimer l'image si elle existe | Vérifier si la photo est un chemin de fichier local
        if ($category->photo && !filter_var($category->photo, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($category->photo);
        }
        $category->delete();
        return response()->json(null, 204);
    }
}
