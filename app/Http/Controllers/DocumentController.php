<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        return response()->json(['success' => true, 'message' => 'Document deleted successfully']);
    }

    public function verify(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $document->update([
            'is_verified' => true,
            'status' => 'approved',
        ]);

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'remarks' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Map the documentable_type to a model namespace
        $modelMap = [
            'shareholder' => 'App\\Models\\Shareholder',
            'sell_application' => 'App\\Models\\SellApplication',
            'buy_application' => 'App\\Models\\BuyApplication',
            'transaction' => 'App\\Models\\Transaction',
            'board_decision' => 'App\\Models\\BoardDecision',
            'notice_publication' => 'App\\Models\\NoticePublication',
        ];

        $documentableType = $modelMap[$validated['documentable_type']] ?? null;

        if (!$documentableType) {
            return back()->withErrors(['documentable_type' => 'Invalid documentable type']);
        }

        // Store the file
        $filePath = $file->storeAs(
            'documents/' . $validated['documentable_type'] . 's/' . $validated['documentable_id'],
            $fileName,
            'public'
        );

        // Create document record
        Document::create([
            'documentable_type' => $documentableType,
            'documentable_id' => $validated['documentable_id'],
            'document_type' => $validated['document_type'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'upload_date' => now(),
            'status' => 'pending',
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully');
    }
}
