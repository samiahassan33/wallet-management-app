<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'balance' => 'required|numeric|min:0',
            ]);
            $user = User::create($validated);
            return response()->json([
                'message' => 'User created successfully.',
                'data' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id) {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
}
