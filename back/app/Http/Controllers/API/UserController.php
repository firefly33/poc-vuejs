<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of fake users.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Get all users with only the necessary fields
        $users = User::select('id', 'name', 'email', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users,
            'count' => $users->count()
        ]);
    }
}