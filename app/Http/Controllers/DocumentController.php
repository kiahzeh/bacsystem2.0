<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function upload(Request $request, PurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'document' => 'required|file|max:10240', // 10MB max
            'status' => 'required|string'
        ]);

        $file = $request->file('document');
        $originalFilename = $file->getClientOriginalName();
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        
        // Store the file
        $path = $file->storeAs('documents/' . $purchaseRequest->id, $filename, 'public');

        // Create document record
        $document = Document::create([
            'purchase_request_id' => $purchaseRequest->id,
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'file_path' => $path,
            'status' => $request->status,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function download(Document $document)
    {
        // Check if user has permission to download
        if (!auth()->user()->can('view', $document->purchaseRequest)) {
            abort(403);
        }

        return Storage::download($document->file_path, $document->original_filename);
    }

    public function destroy(Document $document)
    {
        // Check if user has permission to delete
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        // Delete the file from storage if file_path is not null
        if ($document->file_path) {
            Storage::delete($document->file_path);
        }

        // Delete record from database
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
