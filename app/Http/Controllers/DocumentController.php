<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('documentable')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('documents.index', compact('documents'));
    }

    public function show($id)
    {
        $document = Document::with('documentable')->findOrFail($id);
        return view('documents.show', compact('document'));
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);
        
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function view($id)
    {
        $document = Document::findOrFail($id);
        
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        $file = Storage::disk('public')->get($document->file_path);
        $type = Storage::disk('public')->mimeType($document->file_path);

        return Response::make($file, 200, [
            'Content-Type' => $type,
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"'
        ]);
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        // Delete database record
        $document->delete();

        return back()->with('success', 'Document deleted successfully');
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

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer',
            'document_type' => 'required|string',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/' . strtolower(class_basename($validated['documentable_type'])) . 's/' . $validated['documentable_id'], $fileName, 'public');

        Document::create([
            'documentable_type' => $validated['documentable_type'],
            'documentable_id' => $validated['documentable_id'],
            'document_type' => $validated['document_type'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'upload_date' => now(),
        ]);

        return back()->with('success', 'Document uploaded successfully');
    }
}
