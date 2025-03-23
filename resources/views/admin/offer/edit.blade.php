<div class="container p-2">
    <form action="{{route('offer.update',$edit_data->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
          

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="product_id">Product <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="product_id" id="product_id">
                        <option value="">Choose Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{$edit_data->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="discount_percentage">Discount % </label>
                    <input type="number" name="discount_percentage" class="form-control" value="{{$edit_data->discount_percentage}}" id="discount_percentage"
                        placeholder="Enter Discount %" min="1" max="100">
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="start_date">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control" value="{{$edit_data->start_date}}" id="start_date">
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="end_date">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control" value="{{$edit_data->end_date}}" id="end_date">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div> 