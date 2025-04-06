<form action="{{ route('banner.submit') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
    @csrf
    <div class="container">
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">{{ __('Name') }}</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="name" placeholder="Enter Name" required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="image" class="col-sm-2 col-form-label">{{ __('Image') }}</label>
            <div class="col-sm-10">
                <input type="file" class="form-control" name="image" id="image" required>
                <small id="imageMessage" class="form-text text-muted">Max size: 2MB</small>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-10 offset-sm-2 text-end">
                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
</form>

<script>
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
