<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class TransactionController extends Controller
{
    public function transfer(Request $request) {
        try {
            $validated = $request->validate([
                'from_user_id' => 'required|exists:users,id',
                'to_user_id' => 'required|exists:users,id|different:from_user_id',
                'amount' => 'required|numeric|min:0.01'
            ]);
            DB::transaction(function () use ($validated) {
                $from = User::findOrFail($validated['from_user_id']);
                $to = User::findOrFail($validated['to_user_id']);

                if ($from->balance < $validated['amount']) {
                    throw new \Exception('Insufficient funds');
                }

                $from->balance -= $validated['amount'];
                $to->balance += $validated['amount'];
                $from->save();
                $to->save();

                $from->transactions()->create([
                    'type' => 'transfer_out',
                    'amount' => $validated['amount'],
                    'user_id' => $to->id
                ]);

                $to->transactions()->create([
                    'type' => 'transfer_in',
                    'amount' => $validated['amount'],
                    'user_id' => $from->id
                ]);
            });
            return response()->json(['message' => 'Transfer successful.']);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Transfer failed',
                'error' => $e->getMessage()
            ], 400);
        }

    }

    public function index($id) {
        $user = User::findOrFail($id);
        return response()->json($user->transactions);
    }
}
