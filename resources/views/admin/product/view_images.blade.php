<!-- Product Images Management Page -->

<!-- Image List -->
<div class="row" id="image-list">
    @foreach($edit_data as $img)
    <div class="col-md-3 mb-3" id="img-{{ $img->id }}">
        <div class="card">
            <img src="{{ asset('storage/' . $img->image) }}" id="image" height="120" class="card-img-top" alt="Image">
            <div class="card-body p-2 text-center">
                <button class="btn btn-sm btn-danger delete-image" data-id="{{ $img->id }}">Delete</button>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Upload New Images -->
<form id="uploadImageForm" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="product_id" value="{{ request()->route('id') }}">
    <div class="mb-3">
        <label for="newImages" class="form-label">Upload New Images</label>
        <input type="file" name="images[]" id="newImages" class="form-control" multiple>
        <small id="imageMessage" class="form-text text-muted">Max size: 2MB</small>
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>

<!-- JavaScript -->
<script>
    $(document).ready(function () {
        // Delete image
        $(document).on('click', '.delete-image', function () {
            var imageId = $(this).data('id');
            if (confirm("Are you sure you want to delete this image?")) {
                $.ajax({
                    url: '/product/delete_image/' + imageId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#img-' + imageId).remove();
                    },
                    error: function () {
                        alert('Failed to delete the image.');
                    }
                });
            }
        });

        // Upload new images
        $('#uploadImageForm').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '/product/upload_image',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                // Optional: Show loading indicator
            },
            success: function (response) {
                if (response.success && response.html) {
                    $('#image-list').append(response.html);
                    $('#newImages').val('');
                } else {
                    alert('Upload failed');
                }
            },
            error: function () {
                alert('Upload error occurred.');
            }
        });
});

    });
</script>
<script>
    document.getElementById('newImages').addEventListener('change', function () {
        const file = this.files[0];
        const messageEl = document.getElementById('imageMessage');

        if (file && file.size > 2 * 1024 * 1024) {
            messageEl.textContent = "Image size should not exceed 2MB.";
            messageEl.classList.remove("text-muted");
            messageEl.classList.add("text-danger");
            this.value = ""; // clear input
        } else {
            messageEl.textContent = "Max size: 2MB";
            messageEl.classList.remove("text-danger");
            messageEl.classList.add("text-muted");
        }
    });
</script>
