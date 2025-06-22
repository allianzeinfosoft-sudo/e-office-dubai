<?php

namespace App\Http\Controllers;

use App\Models\BookIssue;
use App\Models\Books;
use App\Models\Employee;
use Illuminate\Http\Request;

class BookIssueController extends Controller
{
    public function index()
    {
        $data['meta_title'] = 'Book Issues';
        $data['issues'] = BookIssue::with('book', 'employee')->latest()->get();
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
            'book_id' => 'required|exists:books,id',
            'issued_to' => 'required|exists:users,id',
            'issue_date' => 'required|date',
        ]);

        $book = Books::find($data['book_id']);
        $book->status = 1; // mark as issued
        $book->save();

        BookIssue::create($data);

        return redirect()->route('e-library.book-issues.index')->with('success', 'Book issued successfully.');
    }

    public function return($id)
    {
        $issue = BookIssue::findOrFail($id);
        $issue->status = 'returned';
        $issue->return_date = now();
        $issue->save();

        $book = $issue->book;
        $book->status = 0; // mark as available
        $book->save();

        return redirect()->back()->with('success', 'Book returned successfully.');
    }
}

