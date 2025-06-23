<?php

namespace App\Http\Controllers;

use App\Models\BookIssue;
use App\Models\Books;
use App\Models\Employee;
use Illuminate\Http\Request;

class BookIssueController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $issues = BookIssue::with(['book', 'employee'])->orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Book issues fetched successfully',
                'data' => $issues->map(function ($issue, $index) {
                    return [
                        'row' => $index + 1,
                        'id' => $issue->id,
                        'book' => [
                            'id' => $issue->book->id ?? '',
                            'title' => $issue->book->title ?? '',
                        ],
                        'employee' => [
                            'id' => $issue->employee->id ?? '',
                            'full_name' => $issue->employee->full_name ?? '',
                        ],
                        'issue_date' => date('d-m-Y', strtotime($issue->issue_date)), //$issue->issue_date,
                        'return_date' => $issue->return_date 
                            ? date('d-m-Y', strtotime($issue->return_date))
                            : null,
                        'status' => match($issue->status) {
                            0 => '<span class="badge bg-warning">Issued</span>',
                            1 => '<span class="badge bg-success">Returned</span>',
                            default => '<span class="badge bg-secondary">Unknown</span>',
                        },
                        'current_status' => $issue->status,
                        'created_at' => optional($issue->created_at)->format('d-m-Y'),
                    ];
                }),
            ]);
        }

        $data['meta_title'] = 'Book Issues';
        $data['books'] = Books::where('status', 0)->get(); // only in-stock
        $data['users'] = Employee::all();
        return view('library.books-issue.index', $data);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required',
            'issued_to' => 'required',
            'issue_date' => 'required|date',
        ]);
        $data['issue_date'] = date('Y-m-d', strtotime($data['issue_date']));
        $book = Books::find($data['book_id']);
        $book->status = 1; // mark as issued
        $book->save();
        BookIssue::create($data);
        return redirect()->route('e-library.book-issues.index')->with('success', 'Book issued successfully.');
    }

    public function return($id){
        $issue = BookIssue::findOrFail($id);
        $issue->status = 1;
        $issue->return_date = date('Y-m-d');
        $issue->save();
        $book = Books::find($issue->book_id);
        $book->status = 0; // mark as available
        $book->save();

        return redirect()->back()->with('success', 'Book returned successfully.');
    }

    public function destroy($id){
        $issue = BookIssue::findOrFail($id);

        try {
            // Set the related book status to Available (0)
            if ($issue->book_id) {
                Books::where('id', $issue->book_id)->update(['status' => 0]);
            }
            // Delete the issue record
            $issue->delete();
            return response()->json([
                'success' => true,
                'message' => 'Book issue deleted successfully and book status updated to available.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete book issue.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

