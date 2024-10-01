<!-- resources/views/books/modal.blade.php -->
<div class="modal fade" id="bookDetailsModal" tabindex="-1" aria-labelledby="bookDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookDetailsModalLabel">Book Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Title: <span id="bookTitle"></span></h5>
                <h6>Author: <span id="bookAuthor"></span></h6>
                <p><strong>ISB:</strong> <span id="bookISB"></span></p>
                <p><strong>Publication Year:</strong> <span id="bookPublicationYear"></span></p>
                <p><strong>Publisher:</strong> <span id="bookPublisher"></span></p>
                <img id="bookCoverImage" src="" alt="Book Cover" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
