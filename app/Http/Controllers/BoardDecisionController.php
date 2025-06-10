<?php

namespace App\Http\Controllers;

use App\Models\BoardDecision;
use App\Models\SellApplication;
use Illuminate\Http\Request;

class BoardDecisionController extends Controller
{
    public function index()
    {
        $decisions = BoardDecision::with('sellApplication.seller')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('board-decisions.index', compact('decisions'));
    }

    public function show($id)
    {
        $decision = BoardDecision::with('sellApplication.seller')->findOrFail($id);
        return view('board-decisions.show', compact('decision'));
    }

    public function create()
    {
        $sellApplications = SellApplication::where('status', 'pending')
            ->with('seller')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('board-decisions.create', compact('sellApplications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sell_application_id' => 'required|exists:sell_applications,id',
            'decision_date' => 'required|date',
            'decision' => 'required|in:approved,rejected',
            'decision_remarks' => 'nullable|string',
            'board_members_present' => 'nullable|string',
            'meeting_minute_reference' => 'nullable|string'
        ]);

        // Check if decision already exists
        $existingDecision = BoardDecision::where('sell_application_id', $validated['sell_application_id'])->first();
        if ($existingDecision) {
            return back()->withErrors(['sell_application_id' => 'Board decision already exists for this application'])
                ->withInput();
        }

        // Convert board members string to array
        if ($request->board_members_present) {
            $validated['board_members_present'] = array_map('trim', explode(',', $request->board_members_present));
        }

        BoardDecision::create($validated);

        // Update sell application status
        $sellApplication = SellApplication::findOrFail($validated['sell_application_id']);
        $newStatus = $validated['decision'] === 'approved' ? 'board_approved' : 'board_rejected';
        $sellApplication->update(['status' => $newStatus]);

        return redirect()->route('board-decisions.index')
            ->with('success', 'Board decision recorded successfully');
    }

    public function edit($id)
    {
        $decision = BoardDecision::with('sellApplication')->findOrFail($id);
        return view('board-decisions.edit', compact('decision'));
    }

    public function update(Request $request, $id)
    {
        $decision = BoardDecision::findOrFail($id);

        $validated = $request->validate([
            'decision_date' => 'required|date',
            'decision' => 'required|in:approved,rejected',
            'decision_remarks' => 'nullable|string',
            'board_members_present' => 'nullable|string',
            'meeting_minute_reference' => 'nullable|string'
        ]);

        // Convert board members string to array
        if ($request->board_members_present) {
            $validated['board_members_present'] = array_map('trim', explode(',', $request->board_members_present));
        }

        $decision->update($validated);

        // Update sell application status if decision changed
        $sellApplication = $decision->sellApplication;
        $newStatus = $validated['decision'] === 'approved' ? 'board_approved' : 'board_rejected';
        $sellApplication->update(['status' => $newStatus]);

        return redirect()->route('board-decisions.index')
            ->with('success', 'Board decision updated successfully');
    }
}
