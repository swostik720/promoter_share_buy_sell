<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\BuyApplication;
use App\Models\SellApplication;
use App\Models\Shareholder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with([
            'sellApplication.seller',
            'buyApplication'
        ])->orderBy('created_at', 'desc')->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with([
            'sellApplication.seller',
            'buyApplication',
            'seller'
        ])->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    public function create()
    {
        $buyApplications = BuyApplication::where('status', 'approved')
            ->with(['sellApplication.seller'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transactions.create', compact('buyApplications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buy_application_id' => 'required|exists:buy_applications,id',
            'share_quantity' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
            'transaction_date' => 'required|date'
        ]);

        $buyApplication = BuyApplication::with('sellApplication')->findOrFail($validated['buy_application_id']);
        $sellApplication = $buyApplication->sellApplication;

        if ($buyApplication->status !== 'approved') {
            return back()->withErrors(['buy_application_id' => 'Buy application must be approved'])
                ->withInput();
        }

        // Calculate total amount
        $totalAmount = $validated['share_quantity'] * $validated['price_per_share'];

        Transaction::create([
            'sell_application_id' => $sellApplication->id,
            'buy_application_id' => $validated['buy_application_id'],
            'seller_id' => $sellApplication->seller_id,
            'share_quantity' => $validated['share_quantity'],
            'price_per_share' => $validated['price_per_share'],
            'total_amount' => $totalAmount,
            'transaction_date' => $validated['transaction_date'],
            'transaction_reference' => 'TXN-' . Str::upper(Str::random(10)),
            'status' => 'pending'
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully');
    }

    public function complete($id)
    {
        $transaction = Transaction::with(['buyApplication', 'seller'])->findOrFail($id);

        if ($transaction->status !== 'pending') {
            return back()->withErrors(['error' => 'Transaction is not in pending status']);
        }

        // Update seller's share quantity
        $seller = $transaction->seller;
        $seller->decrement('share_quantity', $transaction->share_quantity);

        // Create or update buyer as shareholder if they're not existing promoter
        $buyApplication = $transaction->buyApplication;
        if ($buyApplication->buyer_category === 'public') {
            Shareholder::create([
                'name' => $buyApplication->buyer_name,
                'type' => $buyApplication->buyer_type,
                'category' => 'public',
                'share_quantity' => $transaction->share_quantity,
                'citizenship_number' => $buyApplication->citizenship_number,
                'pan_number' => $buyApplication->pan_number,
                'demat_account' => $buyApplication->demat_account,
                'contact_details' => $buyApplication->contact_details
            ]);
        }

        // Update transaction status
        $transaction->update([
            'status' => 'completed',
            'regulatory_notifications' => [
                'sebbon_notified' => now(),
                'nepse_notified' => now(),
                'nia_notified' => now()
            ]
        ]);

        // Update application statuses
        $transaction->sellApplication->update(['status' => 'completed']);
        $buyApplication->update(['status' => 'completed']);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction completed successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,completed,failed'
        ]);

        $transaction->update($validated);

        return back()->with('success', 'Transaction status updated successfully');
    }
}
