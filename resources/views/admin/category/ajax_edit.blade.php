

<div class="container p-2">
    <form action="{{route('categories.update',$edit_data->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="name">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{$edit_data->title}}" placeholder="Enter title" required>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="phone">Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control" id="thumbnail"  >
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div>
