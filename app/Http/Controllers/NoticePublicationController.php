<?php

namespace App\Http\Controllers;

use App\Models\NoticePublication;
use App\Models\SellApplication;
use Illuminate\Http\Request;

class NoticePublicationController extends Controller
{
    public function index()
    {
        $notices = NoticePublication::with('sellApplication.seller')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notice-publications.index', compact('notices'));
    }

    public function show($id)
    {
        $notice = NoticePublication::with('sellApplication.seller')->findOrFail($id);
        return view('notice-publications.show', compact('notice'));
    }

    public function create()
    {
        $sellApplications = SellApplication::where('status', 'board_approved')
            ->with('seller')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notice-publications.create', compact('sellApplications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sell_application_id' => 'required|exists:sell_applications,id',
            'publication_date' => 'required|date',
            'newspaper_name' => 'required|string|max:255',
            'notice_content' => 'required|string',
            'notice_reference' => 'nullable|string'
        ]);

        // Check if sell application is board approved
        $sellApplication = SellApplication::findOrFail($validated['sell_application_id']);
        if ($sellApplication->status !== 'board_approved') {
            return back()->withErrors(['sell_application_id' => 'Sell application must be board approved before notice publication'])
                ->withInput();
        }

        NoticePublication::create($validated);

        // Update sell application status
        $sellApplication->update(['status' => 'notice_published']);

        return redirect()->route('notice-publications.index')
            ->with('success', 'Notice publication recorded successfully');
    }

    public function edit($id)
    {
        $notice = NoticePublication::with('sellApplication')->findOrFail($id);
        return view('notice-publications.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $notice = NoticePublication::findOrFail($id);

        $validated = $request->validate([
            'publication_date' => 'required|date',
            'newspaper_name' => 'required|string|max:255',
            'notice_content' => 'required|string',
            'notice_reference' => 'nullable|string'
        ]);

        $notice->update($validated);

        return redirect()->route('notice-publications.index')
            ->with('success', 'Notice publication updated successfully');
    }
}
