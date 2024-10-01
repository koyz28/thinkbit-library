@extends('layouts.app')

@section('title', 'Books Import/Export')

@section('content')
    <h1>CSV Upload/Export Book</h1>
    <div class="container">
        <!-- Form to upload CSV file -->
        <form action="{{ route('book-upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="csv_file" class="form-label">Upload CSV</label>
                <input type="file" class="form-control" id="csv_file" name="csv_file" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload CSV</button>
        </form>

        <!-- Button to export book list as CSV -->
        <div class="mt-4">
            <a href="{{ route('book-export') }}" class="btn btn-success">Export Book List as CSV</a>
        </div>
    </div>
@endsection