<?php

namespace App\Http\Controllers;

use App\Models\SellApplication;
use App\Models\Shareholder;
use Illuminate\Http\Request;

class SellApplicationController extends Controller
{
    public function index()
    {
        $applications = SellApplication::with(['seller', 'boardDecision', 'noticePublication'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sell-applications.index', compact('applications'));
    }

    public function show($id)
    {
        $application = SellApplication::with([
            'seller',
            'buyApplications',
            'boardDecision',
            'noticePublication',
            'documents',
            'transactions'
        ])->findOrFail($id);

        return view('sell-applications.show', compact('application'));
    }

    public function create()
    {
        $shareholders = Shareholder::where('category', 'promoter')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sell-applications.create', compact('shareholders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'seller_id' => 'required|exists:shareholders,id',
            'share_quantity_to_sell' => 'required|integer|min:1',
            'proposed_price_per_share' => 'nullable|numeric|min:0',
            'application_date' => 'required|date',
            'reason' => 'nullable|string'
        ]);

        // Check if seller has enough shares
        $seller = Shareholder::findOrFail($validated['seller_id']);
        if ($seller->share_quantity < $validated['share_quantity_to_sell']) {
            return back()->withErrors(['share_quantity_to_sell' => 'Insufficient shares to sell'])
                ->withInput();
        }

        SellApplication::create($validated);

        return redirect()->route('sell-applications.index')
            ->with('success', 'Sell application created successfully');
    }

    public function edit($id)
    {
        $application = SellApplication::findOrFail($id);
        $shareholders = Shareholder::where('category', 'promoter')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sell-applications.edit', compact('application', 'shareholders'));
    }

    public function update(Request $request, $id)
    {
        $application = SellApplication::findOrFail($id);

        $validated = $request->validate([
            'seller_id' => 'required|exists:shareholders,id',
            'share_quantity_to_sell' => 'required|integer|min:1',
            'proposed_price_per_share' => 'nullable|numeric|min:0',
            'application_date' => 'required|date',
            'reason' => 'nullable|string'
        ]);

        $application->update($validated);

        return redirect()->route('sell-applications.index')
            ->with('success', 'Sell application updated successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $application = SellApplication::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,board_approved,board_rejected,notice_published,completed,cancelled'
        ]);

        $application->update($validated);

        return back()->with('success', 'Application status updated successfully');
    }
}
