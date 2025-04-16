<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class walletController extends Controller
{
    public function deposit(Request $request) {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:0.01'
            ]);
            $user = User::find($validated['user_id']);
            $user->balance += $validated['amount'];
            $user->save();
            $user->transactions()->create([
                'type' => 'deposit',
                'amount' => $validated['amount']
            ]);
            return response()->json(['message' => 'Deposit successful.']);
        } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Something went wrong during deposit.',
                    'error' => $e->getMessage()
                ], 500);
            }
    }

    public function withdraw(Request $request) {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:0.01'
            ]);
            $user = User::find($validated['user_id']);
            if ($user->balance < $validated['amount']) {
                return response()->json(['message' => 'Insufficient funds.'], 400);
            }
            $user->balance -= $validated['amount'];
            $user->save();
            $user->transactions()->create([
                'type' => 'withdrawal',
                'amount' => $validated['amount']
            ]);
            return response()->json(['message' => 'Withdrawal successful.']);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong during withdrawal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
