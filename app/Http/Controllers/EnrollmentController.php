<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Relations définies dans le modèle Enrollment
            $enrollments = Enrollment::with(['user', 'formation', 'language'])->get();
            return response()->json([
                'success' => true,
                'data' => $enrollments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching enrollments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function allEnrolls()
    {
        try {
            // Relations définies dans le modèle Enrollment
            $enrollments = Enrollment::with(['user', 'formation', 'language'])->get();
            return response()->json([
                'success' => true,
                'enrollments' => $enrollments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching enrollments',
                'error' => $e->getMessage()
            ], 500);
        }
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
    public function store(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'price' => 'required|numeric|min:0',
            'language_id' => 'required|exists:language_mediums,id',
            'promo_code' => 'nullable|string|max:50',
            'comment' => 'nullable|string',
            'start_date' => 'nullable|date',  // Add validation for start_date
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Vérifie si le cours existe
            $formation = Formation::findOrFail($courseId);

            $enrollment = Enrollment::create([
                'user_id' => Auth::id(),
                'fullname' => $request->fullname,
                'phone' => $request->phone,
                'email' => $request->email,
                'formation_id' => $formation->id,
                'price' => $request->price,
                'language_id' => $request->language_id,
                'promo_code' => $request->promo_code,
                'comment' => $request->comment,
                'status' => 'draft', // Défaut
                'start_date' => $request->start_date, // Store the start_date
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enrollment successful',
                'data' => $enrollment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error enrolling in course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->status = 'validated';
        $enrollment->save();

        return response()->json(['success' => true, 'message' => 'Status updated']);
    }

    /**
     * Display the specified resource.
     */
    public function show($courseId, $enrollmentId)
    {
        try {
            // Récupérer l'enrollment par ID et vérifier si l'ID du cours correspond
            $enrollment = Enrollment::where('formation_id', $courseId)
                ->where('id', $enrollmentId)
                ->with(['user', 'formation', 'language'])
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $enrollment
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching enrollment details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($courseId, $enrollmentId)
    {
        try {
            // Récupérer l'enrollment et vérifier que le cours correspond
            $enrollment = Enrollment::where('formation_id', $courseId)
                ->where('id', $enrollmentId)
                ->firstOrFail();

            // Supprimer l'enregistrement
            $enrollment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Enrollment deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting enrollment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
