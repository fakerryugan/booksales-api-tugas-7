<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;

class TransactionController extends Controller
{
   public function index()
    {
       $user = auth('api')->user();
        if ($user->role === 'admin') {
            $transactions = Transaction::with(['user', 'book'])->get();
        } else {
            $transactions = Transaction::with(['user', 'book'])
                ->where('customer_id', $user->id)
                ->get();

        if ($transactions->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Resource data not found!'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get all resources',
            'data' => $transactions
        ]);
    }}

    public function store(Request $request)
    {
        // 1. Validator
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        // 2. Generate unique order number
        $uniqueCode = "ORD-" . strtoupper(uniqid());

        // 3. Ambil user yang login
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized!'
            ], 401);
        }

        // 4. Cari data buku
        $book = Book::find($request->book_id);

        // 5. Cek stok
        if ($book->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok barang tidak cukup!'
            ], 400);
        }

        // 6. Hitung total
        $totalAmount = $book->price * $request->quantity;

        // 7. Kurangi stok
        $book->stock -= $request->quantity;
        $book->save();

        // 8. Simpan transaksi
        $transactions = Transaction::create([
            'order_number' => $uniqueCode,
            'customer_id' => $user->id,
            'book_id' => $request->book_id,
            'total_amount' => $totalAmount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully!',
            'data' => $transactions
        ],201);
    }
    public function show($id)
    {
       $transaction = Transaction::with(['user', 'book'])->find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found!'], 404);
        }

        $user = auth('api')->user();
        if ($user->role !== 'admin' && $transaction->customer_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized! Anda tidak boleh melihat transaksi orang lain.'
            ], 403);
        }
        return response()->json([
            'success' => true,
            'message' => 'Get detail resource',
            'data' => $transaction
        ], 200);
    }
}
