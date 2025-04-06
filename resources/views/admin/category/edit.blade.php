<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <h4 class="mb-0 text-primary">
                <i class="bi bi-pencil-square me-2"></i>Edit Category
            </h4>
        </div>
        
        <form action="{{ route('category.update', $edit_data->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            
            <div class="card-body">
                <!-- Name Field -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-tag-fill text-primary"></i>
                        </span>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" 
                               value="{{ $edit_data->name }}" placeholder="Enter category name" required>
                        <div class="invalid-feedback">
                            Please provide a category name.
                        </div>
                    </div>
                </div>

                <!-- Description Field -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea name="description" id="description" class="form-control" 
                              rows="4" placeholder="Enter category description">{{ $edit_data->description }}</textarea>
                </div>

                 <!-- Has Collection Toggle -->
                 <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" 
                               id="hasCollection" name="has_collection" value="1" {{ $edit_data->has_collection == 1 ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="hasCollection">
                            {{ __('Has Collection') }}
                        </label>
                    </div>
                    <small class="text-muted">Enable if this category contains multiple collections</small>
                </div>

                <!-- Image Upload (if your model has image) -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Current Image</label>
                    <div class="mb-2">
                        <img src="{{ asset($edit_data->icon) }}" alt="Current Image" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                    <label for="image" class="form-label fw-bold">Update Image</label>
                    <input class="form-control" type="file" id="image" name="icon" accept="image/*">
                    <small id="imageMessage" class="form-text text-muted">Max size: 2MB</small>
                </div>

            </div>
            
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle-fill me-2"></i>Update Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap validation script -->
<script>
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
document.getElementById('image').addEventListener('change', function () {
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