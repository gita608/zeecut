<form action="{{ route('category.submit') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ __('Create New Category') }}</h4>
            </div>
            
            <div class="card-body">
                <!-- Name Field -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">{{ __('Name') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-tag-fill text-primary"></i>
                        </span>
                        <input type="text" name="name" class="form-control form-control-lg" id="name" 
                               placeholder="Enter category name" required>
                        <div class="invalid-feedback">
                            Please provide a category name.
                        </div>
                    </div>
                </div>

                <!-- Description Field -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">{{ __('Description') }}</label>
                    <textarea name="description" id="description" class="form-control" 
                              rows="4" placeholder="Enter a brief description..."></textarea>
                </div>

                <!-- Has Collection Toggle -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" 
                               id="hasCollection" name="has_collection" value="1">
                        <label class="form-check-label fw-bold" for="hasCollection">
                            {{ __('Has Collection') }}
                        </label>
                    </div>
                    <small class="text-muted">Enable if this category contains multiple collections</small>
                </div>

                <!-- Image Upload (added for better UX) -->
                <div class="mb-4">
                    <label for="image" class="form-label fw-bold">{{ __('Category Image') }}</label>
                    <input class="form-control" type="file" id="image" name="icon" accept="image/*">
                    <small id="imageMessage" class="form-text text-muted">Max size: 2MB</small>
                </div>
                
            </div>
            
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between">
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>{{ __('Reset') }}
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ __('Submit') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

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