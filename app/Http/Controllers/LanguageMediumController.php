<?php

namespace App\Http\Controllers;

use App\Models\LanguageMedium;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LanguageMediumController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $languages = LanguageMedium::all();
            return response()->json([
                'success' => true,
                'languages' => $languages
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching language mediums',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
