<?php

namespace App\Http\Controllers;

use App\Models\QuickNote;
use App\Models\QuickNoteComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuickNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['meta_title'] = 'Quick Notes';
        $data['quick_notes'] = QuickNote::with('assignedTo', 'createdBy')
        ->withCount('comments')
        ->where(function ($query) {
            $query->where('created_by', Auth::id())
                ->orWhere('assigned_to', Auth::id());
        })->orderBy('created_at', 'desc')
        ->get();
        return view('tools.quick-note.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validateion = $request->validate([
            'title' => 'required',
            'note_description' => 'required',
        ]);

        QuickNote::updateOrCreate(
            [
                'id' => $request->id,
            ], [
                'title' => $request->title,
                'note_description' => $request->note_description,
                'assigned_to' => $request->assigned_to,
                'created_by' => Auth::user()->id,
            ]
        );
        return redirect()->back()->with('success', 'Thoughts created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(QuickNote $quickNote){
        //
        $quickNote->load(['assignedTo', 'createdBy']);

        $data['meta_title'] = 'Quick Notes View';
        $data['quick_note'] = $quickNote;
        $data['comments'] = QuickNoteComments::with('employee')->where('quick_note_id', $quickNote->id)->orderBy('created_at', 'desc')->get();
        $data['html'] = view('tools.quick-note.view', $data)->render();
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuickNote $quickNote)
    {
        $data['quick_note'] = $quickNote;
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuickNote $quickNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $quickNote = QuickNote::find($id);
        $quickNote->delete();
        QuickNoteComments::where('quick_note_id', $id)->delete();
        return redirect()->back()->with('success', 'Thoughts deleted successfully!');
    }

    public function storeComment(Request $request){
    $request->validate([
        'quick_note_id' => 'required|exists:quick_notes,id',
        'note_comment' => 'required|string|max:1000',
    ]);

    $comment = QuickNoteComments::create([
        'quick_note_id' => $request->quick_note_id,
        'comment' => $request->note_comment,
        'commented_by' => Auth::id(),
    ]);

    // Optional: Load user relationship if needed
    $comment->load('employee');

    return response()->json([
        'status' => true,
        'message' => 'Comment added successfully.',
        'comment' => view('tools.quick-note._single-comment', compact('comment'))->render()
    ]);
}
}
