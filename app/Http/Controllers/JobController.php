<?php

namespace App\Http\Controllers;

use App\Models\JobComment;
use App\Models\UserJob;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{

    public function index()
    {
        $data['meta_title'] = 'Jobs';
        $data['jobs'] = UserJob::with('assignedTo', 'createdBy')
        ->withCount('comments')
        ->where(function ($query) {
            $query->where('created_by', Auth::id())
                ->orWhere('assigned_to', Auth::id());
        })->orderBy('created_at', 'desc')
        ->get();
        return view('jobs.index', $data);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validateion = $request->validate([
            'title' => 'required',
            'job_description' => 'required',
        ]);

        UserJob::updateOrCreate(
            [
                'id' => $request->id,
            ], [
                'title' => $request->title,
                'job_description' => $request->job_description,
                'assigned_to' => $request->assigned_to,
                'created_by' => Auth::user()->id,
            ]
        );
        return redirect()->back()->with('success', 'Jobs created successfully!');
    }

    public function show(UserJob $Job)
    {
        $Job->load(['assignedTo', 'createdBy']);

        $data['meta_title'] = 'Jobs View';
        $data['job'] = $Job;
        $data['comments'] = JobComment::with('employee')->where('job_id', $Job->id)->orderBy('created_at', 'desc')->get();
        $data['html'] = view('jobs.view', $data)->render();
        return response()->json($data);
    }


    public function edit(UserJob $Job)
    {
       $data['job'] = $Job;
        return response()->json($data);
    }


    public function update(Request $request, UserJob $Job)
    {
        //
    }

    public function destroy($id)
    {
        $job = UserJob::find($id);
        $job->delete();
        JobComment::where('job_id', $id)->delete();
        return redirect()->back()->with('success', 'User Job deleted successfully!');
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:user_jobs,id',
            'job_comment' => 'required|string|max:1000',
        ]);

        $comment = JobComment::create([
            'job_id' => $request->job_id,
            'comment' => $request->job_comment,
            'commented_by' => Auth::id(),
        ]);

        // Optional: Load user relationship if needed
        $comment->load('employee');

        return response()->json([
            'status' => true,
            'message' => 'Comment added successfully.',
            'comment' => view('jobs._single-comment', compact('comment'))->render()
        ]);
    }
}
