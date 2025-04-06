<div class="container p-3">
    <form action="{{ route('banner.update', $edit_data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Name Field --}}
        <div class="row mb-3">
            <label for="title" class="col-sm-2 col-form-label">{{ __('Name') }}</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title"
                    value="{{ $edit_data->title }}" placeholder="Enter Name" required>
            </div>
        </div>

        {{-- Image Upload --}}
        <div class="row mb-3">
            <label for="image" class="col-sm-2 col-form-label">{{ __('Image') }}</label>
            <div class="col-sm-10">
                <input type="file" name="image" id="image" class="form-control">
                <small id="imageMessage" class="form-text text-muted">Max size: 2MB</small>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="row">
            <div class="col-sm-10 offset-sm-2 text-end">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('image').addEventListener('change', function () {
        const file = this.files[0];
        const messageEl = document.getElementById('imageMessage');

        if (file && file.size > 2 * 1024 * 1024) {
            messageEl.textContent = "Image size should not exceed 2MB.";
            messageEl.classList.remove("text-muted");
            messageEl.classList.add("text-danger");
            this.value = ""; // Clear input
        } else {
            messageEl.textContent = "Max size: 2MB";
            messageEl.classList.remove("text-danger");
            messageEl.classList.add("text-muted");
        }
    });
</script>
