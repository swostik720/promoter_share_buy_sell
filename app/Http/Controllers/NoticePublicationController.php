<?php

namespace App\Http\Controllers;

use App\Models\NoticePublication;
use App\Models\SellApplication;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoticePublicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notices = NoticePublication::with('sellApplication.seller')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get statistics
        $totalNotices = NoticePublication::count();
        $thisMonthNotices = NoticePublication::whereMonth('publication_date', now()->month)
            ->whereYear('publication_date', now()->year)
            ->count();

        return view('notice-publications.index', compact('notices', 'totalNotices', 'thisMonthNotices'));
    }

    public function show($id)
    {
        $notice = NoticePublication::with(['sellApplication.seller', 'documents'])->findOrFail($id);
        return view('notice-publications.show', compact('notice'));
    }

    public function create()
    {
        $sellApplications = SellApplication::where('status', 'board_approved')
            ->with('seller', 'boardDecision')
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
            'notice_attachment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Check if sell application is board approved
        $sellApplication = SellApplication::findOrFail($validated['sell_application_id']);
        if ($sellApplication->status !== 'board_approved') {
            return back()->withErrors(['sell_application_id' => 'Sell application must be board approved before notice publication'])
                ->withInput();
        }

        // Create notice publication
        $noticePublication = NoticePublication::create([
            'sell_application_id' => $validated['sell_application_id'],
            'publication_date' => $validated['publication_date'],
            'newspaper_name' => $validated['newspaper_name'],
        ]);

        // Handle document upload
        if ($request->hasFile('notice_attachment')) {
            $file = $request->file('notice_attachment');
            $fileName = time() . '_notice_attachment.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents/notice-publications/' . $noticePublication->id, $fileName, 'public');

            // Create document record
            Document::create([
                'documentable_type' => get_class($noticePublication),
                'documentable_id' => $noticePublication->id,
                'document_type' => 'notice_publication',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'upload_date' => now(),
                'status' => 'pending',
            ]);
        }

        // Update sell application status
        $sellApplication->update(['status' => 'notice_published']);

        return redirect()->route('notice-publications.index')
            ->with('success', 'Notice publication recorded successfully');
    }

    public function edit($id)
    {
        $notice = NoticePublication::with(['sellApplication', 'documents'])->findOrFail($id);
        return view('notice-publications.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $notice = NoticePublication::findOrFail($id);

        $validated = $request->validate([
            'publication_date' => 'required|date',
            'newspaper_name' => 'required|string|max:255',
            'notice_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $notice->update([
            'publication_date' => $validated['publication_date'],
            'newspaper_name' => $validated['newspaper_name'],
        ]);

        // Handle document upload if provided
        if ($request->hasFile('notice_attachment')) {
            $file = $request->file('notice_attachment');
            $fileName = time() . '_notice_attachment.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents/notice-publications/' . $notice->id, $fileName, 'public');

            // Delete existing document if any
            $existingDoc = $notice->documents()->where('document_type', 'notice_publication')->first();
            if ($existingDoc) {
                Storage::disk('public')->delete($existingDoc->file_path);
                $existingDoc->delete();
            }

            // Create new document record
            Document::create([
                'documentable_type' => get_class($notice),
                'documentable_id' => $notice->id,
                'document_type' => 'notice_publication',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'upload_date' => now(),
                'status' => 'pending',
            ]);
        }

        return redirect()->route('notice-publications.index')
            ->with('success', 'Notice publication updated successfully');
    }
}
