<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json([]);
    }
}
