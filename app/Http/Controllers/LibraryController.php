<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BooksCategory;
use App\Models\Books;
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
        //
        $data['meta_title'] = 'Books Categories';
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
}
