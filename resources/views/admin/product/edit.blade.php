<div class="container p-2">
    <form action="{{ route('product.update', $edit_data->id) }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Category -->
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                    <select class="form-control" name="category" id="category" onchange="get_category_id(this.value)">
                        <option value="">Choose Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $edit_data->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Title -->
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" value="{{ $edit_data->name }}" placeholder="Enter Title" required>
                </div>
            </div>

            <!-- Description -->
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="description" id="description">{{ $edit_data->description }}</textarea>
                </div>
            </div>

            <!-- Unit -->
            <div class="col-md-6">
                <label class="form-label">Unit<span class="text-danger">*</span></label>
                <select name="unit" class="form-control" onchange="get_price_label(this.value)">
                    <option value="">Choose Unit</option>
                    <option value="1" {{ $edit_data->unit == 1 ? 'selected' : '' }}>Kg</option>
                    <option value="2" {{ $edit_data->unit == 2 ? 'selected' : '' }}>Liter</option>
                    <option value="3" {{ $edit_data->unit == 3 ? 'selected' : '' }}>Quantity</option>
                </select>
            </div>

            <!-- Price -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" id="price_label">Price <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" value="{{ $edit_data->price }}" placeholder="Enter price" required>
                </div>
            </div>

            <!-- Discount Price -->
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Discount Price</label>
                    <input type="number" name="discount_price" value="{{ $edit_data->discount_price }}" class="form-control" placeholder="Enter Discount price">
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Primary Image</label>
                    <input type="file" name="thumbnail" class="form-control" id="thumbnailInput">
                    <div id="thumbnailError" class="text-danger mt-1" style="display:none;"></div>
                    <img src="{{ asset('uploads/' . $edit_data->thumbnail) }}" alt="Primary Image" class="mt-2" width="100px">
                </div>
            </div>

            <!-- Collections -->
            <div id="collection_div">
                <div class="col-md-12">
                    <label class="form-label">Number of Collections</label>
                    <input type="number" id="inputCount" name="no_of_collection" value="" class="form-control mb-3" placeholder="Enter number of collections">
                    <button type="button" class="btn btn-primary mb-3" id="generateInputs">Generate</button>
                </div>

                <div class="col-md-12" id="dynamicInputs">
                    @if($collections)
                        @foreach($collections as $index => $collection)
                        <div class="d-flex align-items-center mb-3 p-2 bg-light rounded shadow-sm" data-index="{{ $index + 1 }}">
                            <input type="text" class="form-control me-3" name="collection_title[]" value="{{ $collection->title }}" placeholder="Collection Title">
                            <input type="number" class="form-control me-3" name="collection_price[]" value="{{ $collection->price }}" placeholder="Collection Price">
                            <input type="number" class="form-control me-3" name="collection_sale_price[]" value="{{ $collection->sale_price }}" placeholder="Collection Discount Price">
                            
                            @if($index > 0)
                            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div>

<!-- SCRIPTS -->
<script>
    function get_price_label(value){
        if(value == 1){
            $('#price_label').html('Price Per Kg <span class="text-danger">*</span>');
        } else if(value == 2){
            $('#price_label').html('Price Per Liter <span class="text-danger">*</span>');
        } else {
            $('#price_label').html('Price Per Quantity <span class="text-danger">*</span>');
        }
    }

    $(document).ready(function(){
        @if(!empty($collection))
            get_price_label("{{ $collection->unit }}");
        @endif
    });
</script>

<script>
    // AJAX Category Check
    $(document).ready(function () {
        const category_id = $('#category').val();
        get_category_id(category_id);
    });

    function get_category_id(category_id) {
        $.ajax({
            url: @json(route('product.get_has_collection')),
            type: "GET",
            data: { category_id },
            success: function (response) {
                if (response.status === "success" && response.category) {
                    if (response.category.has_collection == 0) {
                        $('#collection_div').hide();
                    } else {
                        $('#collection_div').show();
                    }
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    // Generate Dynamic Collection Inputs
    document.getElementById('generateInputs').addEventListener('click', function () {
        const inputCount = parseInt(document.getElementById('inputCount').value);
        const dynamicInputsContainer = document.getElementById('dynamicInputs');

        let currentIndex = dynamicInputsContainer.children.length;

        if (!isNaN(inputCount) && inputCount > 0) {
            for (let i = 0; i < inputCount; i++) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'd-flex align-items-center mb-3 p-2 bg-light rounded shadow-sm';

                const titleInput = document.createElement('input');
                titleInput.type = 'text';
                titleInput.className = 'form-control me-3';
                titleInput.name = 'collection_title[]';
                titleInput.placeholder = 'Collection Title';

                const priceInput = document.createElement('input');
                priceInput.type = 'number';
                priceInput.className = 'form-control me-3';
                priceInput.name = 'collection_price[]';
                priceInput.placeholder = 'Collection Price';

                const collectionSalePriceInput = document.createElement('input');
                collectionSalePriceInput.type = 'number';
                collectionSalePriceInput.className = 'form-control me-3';
                collectionSalePriceInput.name = 'collection_sale_price[]';
                collectionSalePriceInput.placeholder = 'Collection Discount Price';

                rowDiv.appendChild(titleInput);
                rowDiv.appendChild(priceInput);
                rowDiv.appendChild(collectionSalePriceInput);

                // Only add remove button if not the very first row
                if (currentIndex > 0 || i > 0) {
                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'btn btn-danger btn-sm remove-row';
                    removeBtn.innerHTML = '<i class="fas fa-trash"></i>';
                    removeBtn.type = 'button';
                    removeBtn.addEventListener('click', function () {
                        rowDiv.remove();
                    });
                    rowDiv.appendChild(removeBtn);
                }

                dynamicInputsContainer.appendChild(rowDiv);
                currentIndex++;
            }
        }
    });

    // Remove Dynamic Rows
    document.addEventListener('click', function (event) {
        if (event.target.closest('.remove-row')) {
            event.target.closest('div').remove();
        }
    });

    // File Size Validation for Thumbnail (Limit: 2MB)
    document.getElementById('thumbnailInput').addEventListener('change', function () {
        const file = this.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const errorDiv = document.getElementById('thumbnailError');

        if (file && file.size > maxSize) {
            errorDiv.innerText = 'File size should not exceed 2MB.';
            errorDiv.style.display = 'block';
            this.value = '';
        } else {
            errorDiv.innerText = '';
            errorDiv.style.display = 'none';
        }
    });
</script>
