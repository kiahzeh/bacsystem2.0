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
            'path' => $path,
            'status' => $request->status,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id()
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function download(Document $document)
    {
        // Check if user has permission to download
        if (!auth()->user()->can('view', $document->purchaseRequest)) {
            abort(403);
        }

        // Check if file path exists
        if (!$document->path) {
            abort(404, 'File path not found');
        }

        // Check if file exists in storage
        if (!Storage::exists($document->path)) {
            abort(404, 'File not found in storage');
        }

        return Storage::download($document->path, $document->original_filename);
    }

    public function view(Document $document)
    {
        // Check if user has permission to view
        if (!auth()->user()->can('view', $document->purchaseRequest)) {
            abort(403);
        }

        // Check if file path exists
        if (!$document->path) {
            abort(404, 'File path not found');
        }

        // Check if file exists in storage
        if (!Storage::exists($document->path)) {
            abort(404, 'File not found in storage');
        }

        // Get file content
        $file = Storage::get($document->path);
        
        // Return file with appropriate headers for viewing in browser
        return response($file)
            ->header('Content-Type', $document->mime_type)
            ->header('Content-Disposition', 'inline; filename="' . $document->original_filename . '"');
    }

    public function destroy(Document $document)
    {
        // Check if user has permission to delete
        $user = auth()->user();
        $isAdmin = false;
        if ($user) {
            if (isset($user->role) && $user->role === 'admin') {
                $isAdmin = true;
            } elseif (isset($user->is_admin) && ((int) $user->is_admin === 1 || $user->is_admin === true)) {
                $isAdmin = true;
            } elseif (method_exists($user, 'isAdmin')) {
                try { $isAdmin = (bool) $user->isAdmin(); } catch (\Throwable $e) { $isAdmin = false; }
            }
        }
        if (!$isAdmin) {
            abort(403);
        }

        // Delete the file from storage if path exists and file exists
        if ($document->path && Storage::exists($document->path)) {
            Storage::delete($document->path);
        }

        // Delete record from database
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }

    public function approve(Document $document)
    {
        // Check if user has permission to approve
        $user = auth()->user();
        $isAdmin = false;
        if ($user) {
            if (isset($user->role) && $user->role === 'admin') {
                $isAdmin = true;
            } elseif (isset($user->is_admin) && ((int) $user->is_admin === 1 || $user->is_admin === true)) {
                $isAdmin = true;
            } elseif (method_exists($user, 'isAdmin')) {
                try { $isAdmin = (bool) $user->isAdmin(); } catch (\Throwable $e) { $isAdmin = false; }
            }
        }
        if (!$isAdmin) {
            abort(403);
        }

        $document->approve();

        return redirect()->back()->with('success', 'Document approved successfully.');
    }

    public function reject(Request $request, Document $document)
    {
        // Check if user has permission to reject
        $user = auth()->user();
        $isAdmin = false;
        if ($user) {
            if (isset($user->role) && $user->role === 'admin') {
                $isAdmin = true;
            } elseif (isset($user->is_admin) && ((int) $user->is_admin === 1 || $user->is_admin === true)) {
                $isAdmin = true;
            } elseif (method_exists($user, 'isAdmin')) {
                try { $isAdmin = (bool) $user->isAdmin(); } catch (\Throwable $e) { $isAdmin = false; }
            }
        }
        if (!$isAdmin) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $document->reject($request->rejection_reason);

        return redirect()->back()->with('success', 'Document rejected successfully.');
    }
}
