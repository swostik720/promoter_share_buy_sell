<?php

namespace App\Http\Controllers;

use App\Models\BuyApplication;
use App\Models\SellApplication;
use Illuminate\Http\Request;

class BuyApplicationController extends Controller
{
    public function index()
    {
        $applications = BuyApplication::with(['sellApplication.seller'])
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

        return view('buy-applications.create', compact('sellApplications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sell_application_id' => 'required|exists:sell_applications,id',
            'buyer_name' => 'required|string|max:255',
            'buyer_type' => 'required|in:individual,institutional',
            'buyer_category' => 'required|in:existing_promoter,public',
            'share_quantity_to_buy' => 'required|integer|min:1',
            'offered_price_per_share' => 'required|numeric|min:0',
            'application_date' => 'required|date',
            'citizenship_number' => 'nullable|string',
            'pan_number' => 'nullable|string',
            'demat_account' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email'
        ]);

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

        BuyApplication::create($validated);

        return redirect()->route('buy-applications.index')
            ->with('success', 'Buy application created successfully');
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
}
