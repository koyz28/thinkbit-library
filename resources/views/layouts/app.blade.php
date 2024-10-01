<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Default Title')</title>
    <!-- Include the compiled Bootstrap CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav>
        <ul>
            <li><a href="{{ route('book-index') }}">Book List</a></li>
            <li><a href="{{ route('book-create') }}">Book Create</a></li>
            <li><a href="{{ route('book-csv') }}">Book CSV</a></li>
        </ul>
    </nav>
    <div class="container">
        @yield('content')
    </div>

    <!-- Include the compiled Bootstrap JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>
