<?php

namespace App\Http\Controllers;

use App\Models\Shareholder;
use Illuminate\Http\Request;

class ShareholderController extends Controller
{
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
            'pan_number' => 'nullable|string',
            'demat_account' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email'
        ]);

        $contactDetails = [];
        if ($request->phone) $contactDetails['phone'] = $request->phone;
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
            'pan_number' => 'nullable|string',
            'demat_account' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email'
        ]);

        $contactDetails = [];
        if ($request->phone) $contactDetails['phone'] = $request->phone;
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
}
