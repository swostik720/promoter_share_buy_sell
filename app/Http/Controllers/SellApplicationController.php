<?php

namespace App\Http\Controllers;

use App\Models\SellApplication;
use App\Models\Shareholder;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellApplicationController extends Controller
{
    public function index()
    {
        $applications = SellApplication::with(['seller', 'boardDecision', 'noticePublication', 'documents'])
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
            'demat_account' => 'required|string',
            'reason' => 'nullable|string',
            
            // Document uploads
            'sell_application_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'seller_citizenship' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'seller_tax_clearance' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'seller_cia_report' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'seller_moa_aoa' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'seller_decision_minute' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'seller_others' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Check if seller has enough shares
        $seller = Shareholder::findOrFail($validated['seller_id']);
        if ($seller->share_quantity < $validated['share_quantity_to_sell']) {
            return back()->withErrors(['share_quantity_to_sell' => 'Insufficient shares to sell'])
                ->withInput();
        }

        // Create sell application
        $sellApplication = SellApplication::create([
            'seller_id' => $validated['seller_id'],
            'share_quantity_to_sell' => $validated['share_quantity_to_sell'],
            'proposed_price_per_share' => $validated['proposed_price_per_share'],
            'application_date' => $validated['application_date'],
            'demat_account' => $validated['demat_account'],
            'reason' => $validated['reason'],
        ]);

        // Handle document uploads
        $this->handleDocumentUploads($request, $sellApplication, 'sell');

        return redirect()->route('sell-applications.show', $sellApplication->id)
            ->with('success', 'Sell application created successfully with documents');
    }

    public function edit($id)
    {
        $application = SellApplication::with('documents')->findOrFail($id);
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
            'demat_account' => 'required|string',
            'reason' => 'nullable|string'
        ]);

        $application->update($validated);

        // Handle new document uploads if any
        if ($request->hasAnyFile(['sell_application_doc', 'seller_citizenship', 'seller_tax_clearance', 'seller_cia_report', 'seller_moa_aoa', 'seller_decision_minute', 'seller_others'])) {
            $this->handleDocumentUploads($request, $application, 'sell');
        }

        return redirect()->route('sell-applications.show', $application->id)
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

    private function handleDocumentUploads(Request $request, $application, $type)
    {
        $documentTypes = [
            'sell_application_doc' => 'sell_application',
            'seller_citizenship' => 'seller_citizenship',
            'seller_tax_clearance' => 'seller_tax_clearance',
            'seller_cia_report' => 'seller_cia_report',
            'seller_moa_aoa' => 'seller_moa_aoa',
            'seller_decision_minute' => 'seller_decision_minute',
            'seller_others' => 'seller_others',
        ];

        foreach ($documentTypes as $fileKey => $docType) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = time() . '_' . $fileKey . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('documents/sell-applications/' . $application->id, $fileName, 'public');

                // Delete existing document of same type
                $existingDoc = $application->documents()->where('document_type', $docType)->first();
                if ($existingDoc) {
                    Storage::disk('public')->delete($existingDoc->file_path);
                    $existingDoc->delete();
                }

                // Create new document record
                Document::create([
                    'documentable_type' => get_class($application),
                    'documentable_id' => $application->id,
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
