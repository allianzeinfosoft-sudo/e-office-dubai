<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="book_offcanvas" aria-labelledby="book_offcanvas_label">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i> 
            <span id="ksp-offcanvas-title">
                <h5 class="offcanvas-title text-white">Create Books</h5>
                <span class="text-white slogan">Create New Books</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <form id="book-stock-form" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="book_id" id="book_id">

            <div class="mb-3">
                <label for="reg_no" class="form-label">Ref. No. <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="reg_no" id="reg_no" required>
            </div>

            <div class="mb-3">
                <label for="book_title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title" id="book_title" required>
            </div>

            <div class="mb-3">
                <label for="book_author" class="form-label">Author <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="author" id="book_author" required>
            </div>

            <div class="mb-3">
                <label for="book_category" class="form-label">Category <span class="text-danger">*</span></label>
                <select class="+ select2" name="category_id" id="book_category" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="book_description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="book_description" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="book_cover" class="form-label">Cover Image</label>
                <input type="file" class="form-control" name="cover" id="book_cover" accept="image/*">
                <div class="mt-2" id="book-cover-preview" style="display: none;">
                    <img src="" class="img-thumbnail" style="max-width: 150px;">
                </div>
            </div>

            <div class="mb-3">
                <label for="book_status" class="form-label">Status</label>
                <select name="status" id="book_status" class="form-select">
                    <option value="0">In Stock</option>
                    <option value="1">Issued</option>
                    <option value="2">Damaged</option>
                    <option value="3">Lost</option>
                </select>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i>&nbsp; &nbsp;Save Book
                </button>
            </div>
        </form>
    </div>
</div>

@push('js') 
<script>
    $(function () {
       $('#book-stock-form').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const formData = new FormData(this);
            const url = "{{ route('e-library.book.save') }}";

            form.find('button[type="submit"]').prop('disabled', true).text('Saving...');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    toastr.success(response.message);
                    const offcanvas = bootstrap.Offcanvas.getInstance($('#book_offcanvas')[0]);
                    if (offcanvas) offcanvas.hide();
                    setTimeout(() => location.reload(), 500);
                },
                error: function (xhr) {
                    let msg = 'Something went wrong';
                    if (xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors).join('<br>');
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }
                    toastr.error(msg);
                },
                complete: function () {
                    form.find('button[type="submit"]').prop('disabled', false).text('Save Book');
                }
            });
        });
    });
</script>
    
@endpush
