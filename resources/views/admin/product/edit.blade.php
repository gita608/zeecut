<div class="container p-2">
    <form action="{{route('product.update',$edit_data->id )}}" method="post" enctype="multipart/form-data">
        @csrf
        <!-- Laravel CSRF token for security -->
        <div class="row">

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                    <select class="form-control" name="category" id="category">
                        <option value="">Choose Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{$edit_data->category_id == $category->id ? 'selected' : '' }} >{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" value="{{$edit_data->name}}" name="title" placeholder="Enter Title" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Description <span class="text-danger">*</span></label>
                    {{-- <input type="text" name="description" class="form-control" id="description"
                        placeholder="Enter description" required> --}}
                    <textarea class="form-control" name="description"  id="description">{{$edit_data->description}}</textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Price <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" value="{{$edit_data->price}}" id="price" placeholder="Enter price"
                        required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Discount Price </label>
                    <input type="number" name="discount_price" value="{{$edit_data->discount_price}}" class="form-control" id="discount_price"
                        placeholder="Enter Discount price">
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Thumbnail </label>
                    <input type="file" name="thumbnail" value="{{$edit_data->thumbnail}}" class="form-control" id="thumbnail" >
                </div>
            </div>

        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div>