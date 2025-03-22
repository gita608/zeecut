<div class="container p-2">
    <form action="{{route('pincode.update',$edit_data->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{$edit_data->name}}"
                        placeholder="Enter Name" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="description" class="col-sm-2 col-form-label">{{ __('Pincode') }}</label> ̰
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="name" name="pincode" value="{{$edit_data->pincode}}"
                    placeholder="Enter Pincode" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div>