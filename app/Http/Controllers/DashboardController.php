<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth('api')->user(); // Get the authenticated user
        return view('dashboard', compact('user')); // Pass user data to the view
    }

    public function index()
    {
        return response()->json(Formation::with('category')->get(), 200);
    }

}
