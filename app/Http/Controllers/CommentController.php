<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($courseId)
    {
        // Récupère le cours avec les commentaires
        $course = Formation::find($courseId);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json([
            'comments' => $course->comments
        ]);
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
        // Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'author' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Récupère le cours et ajoute un commentaire
        $course = Formation::find($courseId);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $comment = new Comment();
        $comment->author = $request->input('author');
        $comment->text = $request->input('text');
        $comment->formation_id = $courseId;  // Clé étrangère vers le cours
        $comment->save();

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($courseId, $commentId)
    {
        // Récupérer le commentaire associé à un cours spécifique
        $comment = Comment::where('formation_id', $courseId)->find($commentId);

        // Vérifier si le commentaire existe
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        // Retourner les données du commentaire
        return response()->json([
            'comment' => $comment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($courseId, $commentId)
    {
        // Récupère le commentaire
        $comment = Comment::where('course_id', $courseId)->find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        // Supprime le commentaire
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
