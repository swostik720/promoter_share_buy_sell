<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('documentable')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('documents.index', compact('documents'));
    }

    public function show($id)
    {
        $document = Document::with('documentable')->findOrFail($id);
        return view('documents.show', compact('document'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer',
            'document_type' => 'required|in:application,citizenship,tax_clearance,cia_report,moa_aoa,decision_minute,income_source,combine_application,police_report,self_declaration,sebbon_notification,nepse_notification,other',
            'file' => 'required|file|max:10240', // 10MB max
            'remarks' => 'nullable|string'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            Document::create([
                'documentable_type' => $validated['documentable_type'],
                'documentable_id' => $validated['documentable_id'],
                'document_type' => $validated['document_type'],
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'upload_date' => now(),
                'remarks' => $validated['remarks'] ?? null
            ]);

            return redirect()->route('documents.index')
                ->with('success', 'Document uploaded successfully');
        }

        return back()->withErrors(['file' => 'No file uploaded']);
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);

        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->download($document->file_path, $document->file_name);
        }

        return back()->withErrors(['error' => 'File not found']);
    }

    public function verify(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $validated = $request->validate([
            'is_verified' => 'required|boolean',
            'remarks' => 'nullable|string'
        ]);

        $document->update($validated);

        return back()->with('success', 'Document verification status updated');
    }
}
