<?php

namespace App\Http\Controllers;

use App\Models\Shareholder;
use Illuminate\Http\Request;

class ShareholderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $shareholders = Shareholder::where('is_active', true)
            ->orderBy('name')
            ->paginate(15);

        return view('shareholders.index', compact('shareholders'));
    }

    public function show($id)
    {
        $shareholder = Shareholder::with(['sellApplications', 'documents'])
            ->findOrFail($id);

        return view('shareholders.show', compact('shareholder'));
    }

    public function create()
    {
        return view('shareholders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:individual,institutional',
            'category' => 'required|in:promoter,public',
            'share_quantity' => 'required|integer|min:1',
            'citizenship_number' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other',
            'contact_number' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'boid' => 'nullable|string',
            'father_name' => 'nullable|string',
            'grandfather_name' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'pan_number' => 'nullable|string',
            'demat_account' => 'nullable|string'
        ]);

        $contactDetails = [];
        if ($request->contact_number) $contactDetails['phone'] = $request->contact_number;
        if ($request->email) $contactDetails['email'] = $request->email;

        $validated['contact_details'] = $contactDetails;

        Shareholder::create($validated);

        return redirect()->route('shareholders.index')
            ->with('success', 'Shareholder created successfully');
    }

    public function edit($id)
    {
        $shareholder = Shareholder::findOrFail($id);
        return view('shareholders.edit', compact('shareholder'));
    }

    public function update(Request $request, $id)
    {
        $shareholder = Shareholder::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:individual,institutional',
            'category' => 'required|in:promoter,public',
            'share_quantity' => 'required|integer|min:1',
            'citizenship_number' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other',
            'contact_number' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'boid' => 'nullable|string',
            'father_name' => 'nullable|string',
            'grandfather_name' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'pan_number' => 'nullable|string',
            'demat_account' => 'nullable|string'
        ]);

        $contactDetails = [];
        if ($request->contact_number) $contactDetails['phone'] = $request->contact_number;
        if ($request->email) $contactDetails['email'] = $request->email;

        $validated['contact_details'] = $contactDetails;

        $shareholder->update($validated);

        return redirect()->route('shareholders.index')
            ->with('success', 'Shareholder updated successfully');
    }

    public function destroy($id)
    {
        $shareholder = Shareholder::findOrFail($id);
        $shareholder->update(['is_active' => false]);

        return redirect()->route('shareholders.index')
            ->with('success', 'Shareholder deactivated successfully');
    }

    public function getShareholderData($id)
    {
        $shareholder = Shareholder::findOrFail($id);
        return response()->json([
            'type' => $shareholder->type,
            'boid' => $shareholder->boid,
            'demat_account' => $shareholder->demat_account,
            'citizenship_number' => $shareholder->citizenship_number,
            'contact_number' => $shareholder->contact_number,
            'email' => $shareholder->email,
            'pan_number' => $shareholder->pan_number,
        ]);
    }
}
