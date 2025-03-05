<form action="{{ route('categories.submit') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="container">
        <div class="row mb-3">
            <label for="title" class="col-sm-2 col-form-label">{{ __('Title') }}</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" required>
            </div>
        </div>
        
        <div class="row mb-3">
            <label for="thumbnail" class="col-sm-2 col-form-label">{{ __('Thumbnail') }}</label>
            <div class="col-sm-10">
                <input type="file" name="thumbnail" class="form-control" id="thumbnail" >
            </div>
        </div>

        <div class="row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
</form>
