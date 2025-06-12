<?php

namespace App\Http\Controllers;

use App\Models\BuyApplication;
use App\Models\SellApplication;
use App\Models\Shareholder;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuyApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $applications = BuyApplication::with(['sellApplication.seller', 'documents'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('buy-applications.index', compact('applications'));
    }

    public function show($id)
    {
        $application = BuyApplication::with([
            'sellApplication.seller',
            'documents',
            'transactions'
        ])->findOrFail($id);

        return view('buy-applications.show', compact('application'));
    }

    public function create()
    {
        $sellApplications = SellApplication::where('status', 'notice_published')
            ->with('seller')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get shareholders for existing promoter dropdown
        $shareholders = Shareholder::orderBy('name')->get();

        return view('buy-applications.create', compact('sellApplications', 'shareholders'));
    }

    public function getShareholderData($id)
    {
        $shareholder = Shareholder::findOrFail($id);

        return response()->json([
            'name' => $shareholder->name,
            'shareholder_type' => $shareholder->shareholder_type,
            'citizenship_number' => $shareholder->citizenship_number,
            'contact_number' => $shareholder->contact_number,
            'email' => $shareholder->email,
            'boid' => $shareholder->boid,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sell_application_id' => 'required|exists:sell_applications,id',
            'buyer_name' => 'required|string|max:255',
            'buyer_category' => 'required|in:existing_promoter,public',
            'buyer_type' => 'required|in:individual,institutional',
            'share_quantity_to_buy' => 'required|integer|min:1',
            'offered_price_per_share' => 'required|numeric|min:0',
            'application_date' => 'required|date',
            'citizenship_number' => 'required|string',
            'pan_number' => 'nullable|string',
            'demat_account' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',

            // Required document uploads
            'buy_application_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'buyer_citizenship' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'buyer_cia_report' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'buyer_tax_clearance' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'buyer_income_source' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'combine_application' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'self_declaration' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',

            // Conditional documents for institutional buyers
            'police_report' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'buyer_moa_aoa' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'buyer_decision_minute' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'buyer_others' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Additional validation for institutional buyers
        if ($validated['buyer_type'] === 'institutional') {
            $request->validate([
                'buyer_moa_aoa' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'buyer_decision_minute' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
        }

        // Check if sell application allows buying
        $sellApplication = SellApplication::findOrFail($validated['sell_application_id']);
        if ($sellApplication->status !== 'notice_published') {
            return back()->withErrors(['sell_application_id' => 'Sell application is not available for buying'])
                ->withInput();
        }

        // Check if requested quantity is available
        if ($sellApplication->share_quantity_to_sell < $validated['share_quantity_to_buy']) {
            return back()->withErrors(['share_quantity_to_buy' => 'Requested quantity exceeds available shares'])
                ->withInput();
        }

        $contactDetails = [];
        if ($request->phone) $contactDetails['phone'] = $request->phone;
        if ($request->email) $contactDetails['email'] = $request->email;

        $validated['contact_details'] = $contactDetails;

        // Create buy application
        $buyApplication = BuyApplication::create($validated);

        // Handle document uploads
        $this->handleDocumentUploads($request, $buyApplication);

        return redirect()->route('buy-applications.show', $buyApplication->id)
            ->with('success', 'Buy application created successfully with documents');
    }

    public function updateStatus(Request $request, $id)
    {
        $application = BuyApplication::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $application->update($validated);

        return back()->with('success', 'Buy application status updated successfully');
    }

    private function handleDocumentUploads(Request $request, $application)
    {
        $documentTypes = [
            'buy_application_doc' => 'buy_application',
            'buyer_citizenship' => 'buyer_citizenship',
            'buyer_cia_report' => 'buyer_cia_report',
            'buyer_tax_clearance' => 'buyer_tax_clearance',
            'buyer_income_source' => 'buyer_income_source',
            'buyer_moa_aoa' => 'buyer_moa_aoa',
            'buyer_decision_minute' => 'buyer_decision_minute',
            'combine_application' => 'combine_application',
            'police_report' => 'police_report',
            'self_declaration' => 'self_declaration',
            'buyer_others' => 'buyer_others',
        ];

        foreach ($documentTypes as $fileKey => $docType) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = time() . '_' . $fileKey . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('documents/buy-applications/' . $application->id, $fileName, 'public');

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
                    'status' => 'pending',
                ]);
            }
        }
    }
}
