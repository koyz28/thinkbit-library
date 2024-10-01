<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BooksExport; 

class BookResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.book-list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.book-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        try{
            DB::beginTransaction();
            $file = $request->file('cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filepath = $file->storeAs('uploads', $filename, 'public');
            $build = Book::create(
                [
                    'isb' => $request->isb,
                    'author' => $request->author,
                    'title' => $request->title,
                    'publication_year' => $request->publication_year,
                    'publisher' => $request->publisher,
                    'cover' => $filename,
                ]
            );
            DB::commit();
            return redirect('/books');
        }
        catch(\Exception $e){
            DB::rollBack();
            $error = $e->getMessage() . "|| Line Number: " . $e->getTraceAsString();
            return response()->json(['error' => $error], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show($book)
    {
        $data = Book::find($book);
        return view('pages.book-view', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, $book)
    {
        try{
            DB::beginTransaction();
            if($request->hasFile('cover')){
                $file = $request->file('cover');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filepath = $file->storeAs('uploads', $filename, 'public');
                Book::where('id', $book)->update(
                    [
                        'cover_type' => 'file',
                        'cover' => $filename,
                    ]
                );
            }
            
            Book::where('id', $book)->update(
                [
                    'isb' => $request->isb,
                    'author' => $request->author,
                    'title' => $request->title,
                    'publication_year' => $request->publication_year,
                    'publisher' => $request->publisher,
                ]
            );
            DB::commit();
            return redirect('/books');
        }
        catch(\Exception $e){
            DB::rollBack();
            $error = $e->getMessage() . "|| Line Number: " . $e->getTraceAsString();
            return response()->json(['error' => $error], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($book)
    {
        $book = Book::findOrFail($book);
        $book->delete();
        return response()->json(['success' => 'Book succesfully deleted']);
    }

    public function getBooks(Request $request){
        $search = $request->input('search');
        $sortField = $request->input('sortField', 'title'); // Default sort by title
        $sortOrder = $request->input('sortOrder', 'asc'); // Default order is ascending
        $query = Book::query();
        if($search){
            $query->where('title', 'LIKE', "%{$search}%")
                         ->orWhere('author', 'LIKE', "%{$search}%");
        }
        if(in_array($sortField, ['title', 'author'])){
            $query->orderBy($sortField, $sortOrder);
        }
        else{
            $query->orderBy('title', 'ASC');
        }
        $books = $query->get();

        $books->map(function($book) {
            if($book->cover_type == 'file'){
                $book->cover = asset('storage/uploads/' . $book->cover);
            }
            return $book;
        });
        return response()->json($books);
    }

    public function findBook($id){
        $book = Book::find($id);
        if($book->cover_type == 'file'){
            $book->cover = asset('storage/uploads/' . $book->cover);
        }
        return $book;
    }

    public function csv()
    {
        return view('pages.book-csv');
    }

    public function uploadCSV(Request $request){
        try{
            $request->validate([
                'csv_file' => 'required|mimes:csv'
            ]);
            if(($handle = fopen($request->file('csv_file'), 'r')) !== false){
                fgetcsv($handle, 1000, ",");
                while(($row = fgets($handle)) !== false){
                    $data = str_getcsv($row, ",");
                    Book::updateOrCreate(
                        [
                            'isb' => $data[0],
                            'author' => $data[1],
                            'title' => $data[2],
                        ],
                        [
                            'isb' => $data[0],
                            'author' => $data[1],
                            'title' => $data[2],
                            'publication_year' => is_int($data[3]) ? $data[3] : 2024,
                            'publisher' => $data[4],
                            'cover_type' => 'link',
                            'cover' => $data[5],
                        ]
                    );
                }
            }
            return redirect('/books');
        }
        catch(\Exception $e){
            DB::rollBack();
            $error = $e->getMessage() . "|| Line Number: " . $e->getTraceAsString();
            return response()->json(['error' => $error], 500);
        }   
    }

    public function bookExport(){
        return Excel::download(new BooksExport, 'books.csv');
    }
}
