<form action="{{ route('category.submit') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="container">
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">{{ __('Name') }}</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="description" class="col-sm-2 col-form-label">{{ __('Description') }}</label>
            <div class="col-sm-10">
                <textarea name="description" id="description" class="form-control" cols="50" rows="5"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
</form>
