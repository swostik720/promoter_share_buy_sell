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
        $documents = Document::with('documentable')->orderBy('created_at', 'desc')->paginate(15);
        return view('documents.index', compact('documents'));
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        return view('documents.show', compact('document'));
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);
        $path = storage_path('app/public/' . $document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return Response::download($path, $document->file_name);
    }

    public function view($id)
    {
        $document = Document::findOrFail($id);
        $path = storage_path('app/public/' . $document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        $file = file_get_contents($path);
        $type = $document->file_type;

        return response($file, 200)->header('Content-Type', $type);
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'related_type' => 'required|string',
            'related_id' => 'required|integer',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Determine the model class from the related_type
        $modelClass = $this->getModelClass($validated['related_type']);
        if (!$modelClass) {
            return back()->withErrors(['related_type' => 'Invalid related type']);
        }

        // Check if the related record exists
        $related = $modelClass::find($validated['related_id']);
        if (!$related) {
            return back()->withErrors(['related_id' => 'Related record not found']);
        }

        // Store the file
        $filePath = $file->storeAs(
            'documents/' . $validated['related_type'] . '/' . $validated['related_id'],
            $fileName,
            'public'
        );

        // Create document record
        $document = Document::create([
            'documentable_type' => get_class($related),
            'documentable_id' => $validated['related_id'],
            'document_type' => $validated['document_type'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'upload_date' => now(),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Map the documentable_type to a model namespace
        $modelMap = [
            'sell_application' => 'App\\Models\\SellApplication',
            'buy_application' => 'App\\Models\\BuyApplication',
            'transaction' => 'App\\Models\\Transaction',
            'shareholder' => 'App\\Models\\Shareholder',
            'board_decision' => 'App\\Models\\BoardDecision',
            'notice_publication' => 'App\\Models\\NoticePublication',
        ];

        $documentableType = $modelMap[$validated['documentable_type']] ?? null;

        if (!$documentableType || !class_exists($documentableType)) {
            return back()->withErrors(['documentable_type' => 'Invalid documentable type']);
        }

        // Check if the related record exists
        $related = $documentableType::find($validated['documentable_id']);
        if (!$related) {
            return back()->withErrors(['documentable_id' => 'Related record not found']);
        }

        // Store the file
        $filePath = $file->storeAs(
            'documents/' . strtolower(class_basename($documentableType)) . '/' . $validated['documentable_id'],
            $fileName,
            'public'
        );

        // Create document record
        $document = Document::create([
            'documentable_type' => $documentableType,
            'documentable_id' => $validated['documentable_id'],
            'document_type' => $validated['document_type'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'upload_date' => now(),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully');
    }

    public function verify(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $document->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Document verified successfully');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Delete the file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Delete the record
        $document->delete();

        return back()->with('success', 'Document deleted successfully');
    }

    private function getModelClass($type)
    {
        $map = [
            'sell_applications' => 'App\\Models\\SellApplication',
            'buy_applications' => 'App\\Models\\BuyApplication',
            'transactions' => 'App\\Models\\Transaction',
            'shareholders' => 'App\\Models\\Shareholder',
            'board_decisions' => 'App\\Models\\BoardDecision',
            'notice_publications' => 'App\\Models\\NoticePublication',
        ];

        return $map[$type] ?? null;
    }
}
