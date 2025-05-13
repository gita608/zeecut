
<div class="container p-2">
    <form action="{{route('payLater.update',$edit_data->id)}}" method="post" enctype="multipart/form-data">
        @csrf <!-- Laravel CSRF token for security -->

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Credit Limit<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="credit_limit" value="{{$edit_data->credit_limit}}"required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div>
