<?php
namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $processes = Process::orderBy('order')->get();
        return view('processes.index', compact('processes'));
    }

    public function create()
    {
        return view('processes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:processes,name',
            'order' => 'nullable|integer',
        ]);
        Process::create([
            'name' => $request->name,
            'order' => $request->order ?? 0,
            'requires_document' => $request->has('requires_document'),
        ]);
        return redirect()->route('processes.index')->with('success', 'Process created successfully.');
    }

    public function edit(Process $process)
    {
        return view('processes.edit', compact('process'));
    }

    public function update(Request $request, Process $process)
    {
        $request->validate([
            'name' => 'required|string|unique:processes,name,' . $process->id,
            'order' => 'nullable|integer',
        ]);
        $process->update([
            'name' => $request->name,
            'order' => $request->order ?? 0,
            'requires_document' => $request->has('requires_document'),
        ]);
        return redirect()->route('processes.index')->with('success', 'Process updated successfully.');
    }

    public function destroy(Process $process)
    {
        $process->delete();
        return redirect()->route('processes.index')->with('success', 'Process deleted successfully.');
    }
} 