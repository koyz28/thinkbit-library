@extends('layouts.app')

@section('title', 'Books Create')

@section('content')
    <h1>Book Form</h1>
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('book-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="isb" class="form-label">ISB</label>
                <input type="text" class="form-control" id="isb" name="isb" value="{{ old('isb') }}" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Book Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label for="author" class="form-label">Book Author</label>
                <input type="text" class="form-control" id="author" name="author" value="{{ old('author') }}" required>
            </div>

            <div class="mb-3">
                <label for="publication_year" class="form-label">Publication Year</label>
                <input type="number" class="form-control" id="publication_year" name="publication_year" value="{{ old('publication_year') }}" required>
            </div>

            <div class="mb-3">
                <label for="publisher" class="form-label">Publisher</label>
                <input type="text" class="form-control" id="publisher" name="publisher" value="{{ old('publisher') }}" required>
            </div>

            <div class="mb-3">
                <label for="cover" class="form-label">Cover Image</label>
                <input type="file" class="form-control" id="cover" name="cover" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Book</button>
        </form>
    </div>
@endsection