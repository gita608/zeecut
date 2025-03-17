<div class="container p-2">
    <form action="{{route('product.update', $edit_data->id)}}" method="post" enctype="multipart/form-data">
        @csrf
         
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
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" value="{{$edit_data->name}}" placeholder="Enter Title" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="description" id="description">{{$edit_data->description}}</textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Price <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" value="{{$edit_data->price}}" placeholder="Enter price" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Discount Price </label>
                    <input type="number" name="discount_price" value="{{$edit_data->discount_price}}" class="form-control" placeholder="Enter Discount price">
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control">
                    <img src="{{ asset('uploads/' . $edit_data->thumbnail) }}" alt="Thumbnail" class="mt-2" width="100px">
                </div>
            </div>

            <!-- Dynamic Input Section for Collections -->
            <div class="col-md-12">
                <label class="form-label">Number of Collections</label>
                <input type="number" id="inputCount" name="no_of_collection" value="" class="form-control mb-3" placeholder="Enter number of collections">
                <button type="button" class="btn btn-primary mb-3" id="generateInputs">Generate</button>
            </div>

            <div class="col-md-12" id="dynamicInputs">
                @if($collections)
                    @foreach($collections as $index => $collection)
                    <div class="d-flex align-items-center mb-3 p-2 bg-light rounded shadow-sm" data-index="{{$index + 1}}">
                        <input type="text" class="form-control me-3" name="collection_title[]" value="{{$collection->title}}" placeholder="Collection Title">
                        <input type="number" class="form-control me-3" name="collection_price[]" value="{{$collection->price}}" placeholder="Collection Price">
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                    </div>
                    @endforeach
                @endif
            </div>

        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div>

<script>
    document.getElementById('generateInputs').addEventListener('click', function() {
        const inputCount = parseInt(document.getElementById('inputCount').value);
        const dynamicInputsContainer = document.getElementById('dynamicInputs');

        if (!isNaN(inputCount) && inputCount > 0) {
            for (let i = 1; i <= inputCount; i++) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'd-flex align-items-center mb-3 p-2 bg-light rounded shadow-sm';

                const titleInput = document.createElement('input');
                titleInput.type = 'text';
                titleInput.className = 'form-control me-3';
                titleInput.name = `collection_title[]`;
                titleInput.placeholder = `Collection Title`;

                const priceInput = document.createElement('input');
                priceInput.type = 'number';
                priceInput.className = 'form-control me-3';
                priceInput.name = `collection_price[]`;
                priceInput.placeholder = `Collection Price`;

                const removeBtn = document.createElement('button');
                removeBtn.className = 'btn btn-danger btn-sm remove-row';
                removeBtn.innerHTML = '<i class="fas fa-trash"></i>';
                removeBtn.type = 'button';

                removeBtn.addEventListener('click', function() {
                    rowDiv.remove();
                });

                rowDiv.appendChild(titleInput);
                rowDiv.appendChild(priceInput);
                rowDiv.appendChild(removeBtn);

                dynamicInputsContainer.appendChild(rowDiv);
            }
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-row')) {
            event.target.closest('div').remove();
        }
    });
</script>