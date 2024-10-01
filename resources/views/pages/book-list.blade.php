<!-- resources/views/books/index.blade.php -->
@extends('layouts.app')

@section('title', 'Books List')

@section('content')
    <h1>Books List</h1>

    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Search by title or author..." />
    </div>

    <!-- Sort Options -->
    <div class="mb-3">
        <label for="sortField">Sort By:</label>
        <select id="sortField" class="form-select">
            <option value="">Select</option>
            <option value="title">Title</option>
            <option value="author">Author</option>
        </select>

        <label for="sortOrder">Order:</label>
        <select id="sortOrder" class="form-select">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select>
    </div>

    <!-- Books table -->
    <table class="table table-bordered table-responsive" id="bookTable">
        <thead>
            <tr>
                <th>#</th>
                <th>ISB</th>
                <th>Title</th>
                <th>Author</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows will be dynamically loaded here -->
        </tbody>
    </table>

    @include('modals.book-view') <!-- Include the modal component -->
    @include('modals.book-delete') <!-- Include the modal component -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Fetch book data using AJAX
            function fetchBooks(searchQuery = '', sortField = '', sortOrder = '') {
                $.ajax({
                    url: '/api/books',
                    method: 'GET',
                    data: {
                        search: searchQuery,
                        sortField: sortField,
                        sortOrder: sortOrder
                    },
                    success: function(data) {
                        var bookTableBody = $('#bookTable tbody');
                        bookTableBody.empty(); 
                        data.forEach(function(book, index) {
                            var row = `<tr data-id="${book.id}">
                                <td>${index + 1}</td>
                                <td>${book.isb}</td>
                                <td>${book.title}</td>
                                <td>${book.author}</td>
                                <td>
                                    <button 
                                        class="btn btn-primary view-cover" 
                                        data-view="${book.id}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#coverModal">View Cover
                                    </button>
                                    <button class="btn btn-info view-details" 
                                            data-id="${book.id}">
                                        Edit Book
                                    </button>
                                    <button 
                                        class="btn btn-danger delete-modal" 
                                        data-delete="${book.id}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal">Delete Book
                                    </button>
                                </td>
                            </tr>`;
                            bookTableBody.append(row);
                        });
                        // Event handlers for newly added elements
                        bindEventHandlers();
                    }
                });
            }
            
            function bindEventHandlers() {
                // Handle cover image modal display
                $('.view-cover').click(function() {
                    var bookId = $(this).data('view');
                    // Fetch book details
                    $.ajax({
                        url: `/api/books/${bookId}`, // Ensure this route is correct to fetch details
                        method: 'GET',
                        success: function(book) {
                            console.log(book);
                            // Populate modal with book details
                            $('#bookTitle').text(book.title);
                            $('#bookAuthor').text(book.author);
                            $('#bookISB').text(book.isb);
                            $('#bookPublicationYear').text(book.publication_year);
                            $('#bookPublisher').text(book.publisher);
                            $('#bookCoverImage').attr('src', book.cover); // Set cover image

                            // Show the modal
                            $('#bookDetailsModal').modal('show');
                        },
                        error: function() {
                            alert('Error fetching book details.');
                        }
                    });
                });

                $('.view-details').click(function() {
                    var bookId = $(this).data('id');
                    // Redirect to the book details page
                    window.location.href = `/books/show/${bookId}`; // Adjust the URL to your routing
                });

                    // Handle delete button click
                $('.delete-modal').click(function() {
                    var bookId = $(this).data('delete');
                    $('#confirmDelete').data('id', bookId); // Store book ID in the button
                });


                $('#confirmDelete').click(function() {
                    var bookId = $(this).data('id');
                    $.ajax({
                        url: '/books/' + bookId,
                        method: 'DELETE',
                        success: function(response) {
                            $('#deleteModal').modal('hide');
                            // Remove the deleted row from the table
                            $('#bookTable tbody tr[data-id="' + bookId + '"]').remove();
                            alert(response.success); // Show success message
                        },
                        error: function() {
                            alert('Error deleting the book.');
                        }
                    });
                });
            }

            // Initial fetch of all books
            fetchBooks();

            // Search functionality
            $('#search').on('keyup', function() {
                var searchQuery = $(this).val();
                var sortField = $('#sortField').val();
                var sortOrder = $('#sortOrder').val();
                fetchBooks(searchQuery, sortField, sortOrder);
            });

            // Sort functionality
            $('#sortField, #sortOrder').on('change', function() {
                var searchQuery = $('#search').val();
                var sortField = $('#sortField').val();
                var sortOrder = $('#sortOrder').val();
                fetchBooks(searchQuery, sortField, sortOrder);
            });
        });
    </script>
@endsection
