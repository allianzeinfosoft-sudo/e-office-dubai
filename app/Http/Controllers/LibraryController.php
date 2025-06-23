<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BooksCategory;
use App\Models\Books;
use App\Models\BookIssue;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['meta_title'] = 'E-Library';
        
        return view('library.index', $data);
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function books_categories(){
        $data['meta_title'] = 'Books Categories';
        $data['categories'] = BooksCategory::withCount([
            'books as total_books',
            'books as issued_books' => function ($query) {
                $query->where('status', 1); // Issued
            },
            'books as damaged_books' => function ($query) {
                $query->whereIn('status', [2, 3]); // Damaged or Lost
            },
            'books as available_books' => function ($query) {
                $query->where('status', 0); // Available / In Stock
            },
        ])->get();
        return view('library.categories.index', $data);
    }

    public function store_category(Request $request){
        $validate = $request->validate([
            'name' => 'required|string|max:50',
            'parent_id' => 'nullable|integer',
        ]);
        
        $category = BooksCategory::updateOrCreate(
            ['id' => $request->id], 
            ['name' => $request->name,
            'parent_id' => $request->parent_id]); 

        return response()->json([
           'message' => 'Category saved successfully!',
            'data' => $category
        ]);
    }
    public function edit_category(Request $request){
        $category = BooksCategory::find($request->id);
        return response()->json([
            'category' => $category
        ]);
    }
    
    public function delete_category(Request $request){
        $category = BooksCategory::find($request->id);
        $category->delete();   
    }
    public function books(Request $request){
        $query = Books::with('category');

        if ($request->filled('reg_no')) {
            $query->where('reg_no', 'like', '%' . $request->reg_no . '%');
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('author')) {
            $query->where('author', 'like', '%' . $request->author . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
         $data['meta_title'] = 'Books';
        $data['books'] = $query->orderBy('title')->get(); // ✅ You must define $books
        $data['categories'] = BooksCategory::orderBy('name')->get(); // ✅ You must also define $categories

        return view('library.books-stock.index', $data); // ✅ Pass both to view
    }

    public function edit_book($id) {
        $data['meta_title'] = 'Edit Book';
        $data['book'] = Books::find($id); // ✅ You must define $book
        return response()->json($data);
    }

     public function book_destroy($id){
        $book = Books::findOrFail($id);
        // Delete cover if it exists
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }
        $book->delete();
        return response()->json([
            'message' => 'Book deleted successfully.'
        ]);
    }

    public function save_book(Request $request){
        $request->validate([
            'reg_no' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'status' => 'nullable|in:0,1,2,3'
        ]);

        $book = $request->book_id ? Books::findOrFail($request->book_id) : new Books();

        $book->reg_no = $request->reg_no;
        $book->title = $request->title;
        $book->author = $request->author;
        $book->category_id = $request->category_id;
        $book->description = $request->description;
        $book->status = $request->status ?? 0;

        if ($request->hasFile('cover')) {
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover); // delete old
            }
            $book->cover = $request->file('cover')->store('books', 'public'); // ✅ store in 'public' disk
        }

        $book->save();

        return response()->json([
            'message' => $request->book_id ? 'Book updated successfully!' : 'Book added successfully!',
            'data' => $book
        ]);
    }

    /* Reports */

    public function issueReport(){
        $data['meta_title'] = 'Issue Report';
        $data['books'] = Books::all();
        $data['employees'] = Employee::all();
        return view('library.reports.issue', $data);
    }

    public function issueReportData(Request $request)
    {
        $query = BookIssue::with('book', 'employee');

        if ($request->from_date) {
            $query->whereDate('issue_date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('issue_date', '<=', $request->to_date);
        }

        if ($request->book_id) {
            $query->where('book_id', $request->book_id);
        }

        if ($request->author) {
            $query->whereHas('book', function($q) use ($request) {
                $q->where('author', 'like', '%' . $request->author . '%');
            });
        }

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        $issues = $query->orderBy('id', 'desc')->get();

        $data = $issues->map(function ($issue, $index) {
            return [
                'row' => $index + 1,
                'book_title' => $issue->book->title ?? '',
                'book_author' => $issue->book->author ?? '',
                'employee_name' => $issue->employee->full_name ?? '',
                'issue_date' => optional($issue->issue_date)->format('d-m-Y'),
                'return_date' => $issue->return_date ? date('d-m-Y', strtotime($issue->return_date)) : '-',
                'status_label' => $issue->status == 0
                    ? '<span class="badge bg-warning">Pending</span>'
                    : '<span class="badge bg-success">Returned</span>',
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Issue report loaded successfully.',
            'data' => $data
        ]);
    }

    public function pendingReport(){
        $data['meta_title'] = 'Pending Books';
        $data['books'] = Books::with('category');
        $data['employees'] = Employee::all();
        return view('library.reports.pending', $data);
    }

    public function pendingReportData(Request $request){
        $query = BookIssue::with('book', 'employee')->where('status', 0);

        if ($request->from_date) {
            $query->whereDate('issue_date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('issue_date', '<=', $request->to_date);
        }

        if ($request->book_id) {
            $query->where('book_id', $request->book_id);
        }

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        $issues = $query->orderBy('id', 'desc')->get();

        $data = $issues->map(function ($issue, $index) {
            return [
                'row' => $index + 1,
                'book_title' => $issue->book->title ?? '',
                'book_author' => $issue->book->author ?? '',
                'employee_name' => $issue->employee->full_name ?? '',
                'issue_date' => optional($issue->issue_date)->format('d-m-Y'),
                'return_date' => $issue->return_date ? date('d-m-Y', strtotime($issue->return_date)) : '-',
                'status_label' => '<span class="badge bg-warning">Pending</span>',
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Pending report loaded successfully.',
            'data' => $data
        ]);
    }

   public function damagedLostReport(){
        $data['meta_title'] = 'Damaged/Lost Books';
        $data['books'] = Books::all();
        return view('library.reports.damaged-lost-report', $data);
    }

    public function damagedLostReportData(Request $request){
        $query = Books::whereIn('status', [2, 3]); // 2 = Damaged, 3 = Lost

        if ($request->from_date) {
            $query->whereDate('updated_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('updated_at', '<=', $request->to_date);
        }

        if ($request->book_id) {
            $query->where('id', $request->book_id);
        }

        $books = $query->orderBy('updated_at', 'desc')->get();

        $data = $books->map(function ($book, $index) {
            $statusLabel = match ($book->status) {
                2 => '<span class="badge bg-danger">Damaged</span>',
                3 => '<span class="badge bg-dark">Lost</span>',
                default => '<span class="badge bg-secondary">Unknown</span>',
            };

            return [
                'row' => $index + 1,
                'title' => $book->title,
                'author' => $book->author,
                'status_label' => $statusLabel,
                'updated_at' => optional($book->updated_at)->format('d-m-Y H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Damaged/Lost report loaded successfully.',
            'data' => $data
        ]);
    }

}
