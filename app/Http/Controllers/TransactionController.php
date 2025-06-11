<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\BuyApplication;
use App\Models\SellApplication;
use App\Models\Shareholder;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            'seller',
            'documents'
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
            'transaction_date' => 'required|date',
            'sebbon_notification_date' => 'nullable|date',
            'nepse_notification_date' => 'nullable|date',
            'nia_notification_date' => 'nullable|date',
            
            // Regulatory notification documents
            'sebbon_notification_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nepse_notification_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nia_notification_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $buyApplication = BuyApplication::with('sellApplication')->findOrFail($validated['buy_application_id']);
        $sellApplication = $buyApplication->sellApplication;

        if ($buyApplication->status !== 'approved') {
            return back()->withErrors(['buy_application_id' => 'Buy application must be approved'])
                ->withInput();
        }

        // Calculate total amount
        $totalAmount = $validated['share_quantity'] * $validated['price_per_share'];

        $transaction = Transaction::create([
            'sell_application_id' => $sellApplication->id,
            'buy_application_id' => $validated['buy_application_id'],
            'seller_id' => $sellApplication->seller_id,
            'share_quantity' => $validated['share_quantity'],
            'price_per_share' => $validated['price_per_share'],
            'total_amount' => $totalAmount,
            'transaction_date' => $validated['transaction_date'],
            'transaction_reference' => 'TXN-' . Str::upper(Str::random(10)),
            'status' => 'pending',
            'sebbon_notification_date' => $validated['sebbon_notification_date'],
            'nepse_notification_date' => $validated['nepse_notification_date'],
            'nia_notification_date' => $validated['nia_notification_date']
        ]);

        // Handle regulatory notification document uploads
        $this->handleRegulatoryDocuments($request, $transaction);

        return redirect()->route('transactions.show', $transaction->id)
            ->with('success', 'Transaction created successfully with regulatory notification dates and documents');
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

    private function handleRegulatoryDocuments(Request $request, $transaction)
    {
        $documentTypes = [
            'sebbon_notification_doc' => 'sebbon_notification',
            'nepse_notification_doc' => 'nepse_notification',
            'nia_notification_doc' => 'nia_notification',
        ];

        foreach ($documentTypes as $fileKey => $docType) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = time() . '_' . $fileKey . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('documents/transactions/' . $transaction->id, $fileName, 'public');

                // Create new document record
                Document::create([
                    'documentable_type' => get_class($transaction),
                    'documentable_id' => $transaction->id,
                    'document_type' => $docType,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'upload_date' => now(),
                ]);
            }
        }
    }
}
