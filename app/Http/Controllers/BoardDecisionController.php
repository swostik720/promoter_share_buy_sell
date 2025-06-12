<?php

namespace App\Http\Controllers;

use App\Models\BoardDecision;
use App\Models\SellApplication;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoardDecisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $decisions = BoardDecision::with('sellApplication.seller')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('board-decisions.index', compact('decisions'));
    }

    public function show($id)
    {
        $decision = BoardDecision::with(['sellApplication.seller', 'documents'])->findOrFail($id);
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
            'meeting_minute_reference' => 'nullable|string',
            'board_decision_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Check if decision already exists
        $existingDecision = BoardDecision::where('sell_application_id', $validated['sell_application_id'])->first();
        if ($existingDecision) {
            return back()->withErrors(['sell_application_id' => 'Board decision already exists for this application'])
                ->withInput();
        }

        // Create board decision
        $boardDecision = BoardDecision::create([
            'sell_application_id' => $validated['sell_application_id'],
            'decision_date' => $validated['decision_date'],
            'decision' => $validated['decision'],
            'meeting_minute_reference' => $validated['meeting_minute_reference'],
        ]);

        // Handle document upload
        if ($request->hasFile('board_decision_doc')) {
            $file = $request->file('board_decision_doc');
            $fileName = time() . '_board_decision_doc.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents/board-decisions/' . $boardDecision->id, $fileName, 'public');

            // Create document record
            Document::create([
                'documentable_type' => get_class($boardDecision),
                'documentable_id' => $boardDecision->id,
                'document_type' => 'board_decision_minute',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'upload_date' => now(),
                'status' => 'pending',
            ]);
        }

        // Update sell application status
        $sellApplication = SellApplication::findOrFail($validated['sell_application_id']);
        $newStatus = $validated['decision'] === 'approved' ? 'board_approved' : 'board_rejected';
        $sellApplication->update(['status' => $newStatus]);

        return redirect()->route('board-decisions.index')
            ->with('success', 'Board decision recorded successfully');
    }

    public function edit($id)
    {
        $decision = BoardDecision::with(['sellApplication', 'documents'])->findOrFail($id);
        return view('board-decisions.edit', compact('decision'));
    }

    public function update(Request $request, $id)
    {
        $decision = BoardDecision::findOrFail($id);

        $validated = $request->validate([
            'decision_date' => 'required|date',
            'decision' => 'required|in:approved,rejected',
            'meeting_minute_reference' => 'nullable|string',
            'board_decision_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $decision->update([
            'decision_date' => $validated['decision_date'],
            'decision' => $validated['decision'],
            'meeting_minute_reference' => $validated['meeting_minute_reference'],
        ]);

        // Handle document upload if provided
        if ($request->hasFile('board_decision_doc')) {
            $file = $request->file('board_decision_doc');
            $fileName = time() . '_board_decision_doc.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents/board-decisions/' . $decision->id, $fileName, 'public');

            // Delete existing document if any
            $existingDoc = $decision->documents()->where('document_type', 'board_decision')->first();
            if ($existingDoc) {
                Storage::disk('public')->delete($existingDoc->file_path);
                $existingDoc->delete();
            }

            // Create new document record
            Document::create([
                'documentable_type' => get_class($decision),
                'documentable_id' => $decision->id,
                'document_type' => 'board_decision_minute',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'upload_date' => now(),
                'status' => 'pending',
            ]);
        }

        // Update sell application status if decision changed
        $sellApplication = $decision->sellApplication;
        $newStatus = $validated['decision'] === 'approved' ? 'board_approved' : 'board_rejected';
        $sellApplication->update(['status' => $newStatus]);

        return redirect()->route('board-decisions.index')
            ->with('success', 'Board decision updated successfully');
    }
}
