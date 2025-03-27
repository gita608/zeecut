<div class="container p-2">
    <form action="{{route('product.submit')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                    <select class="form-control" name="category" id="category" onchange="get_category_id(this.value)">
                        <option value="">Choose Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="name">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="title" placeholder="Enter Title" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="description" id="description"></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="price">Price/kg <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" id="price" placeholder="Enter Price Per Kg"
                        required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="discount_price">Discount Price </label>
                    <input type="number" name="discount_price" class="form-control" id="discount_price"
                        placeholder="Enter Discount Price">
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="thumbnail">Thumbnail <span class="text-danger">*</span></label>
                    <input type="file" name="thumbnail" class="form-control" id="thumbnail" required>
                </div>
            </div>

            <div class="col-md-12" id="collection_div">
                <div class="mb-3">
                    <label class="form-label" for="inputCount">Number of Collections</label>
                    <input type="number" id="inputCount" name="no_of_collection" class="form-control"
                        placeholder="Enter number of collections to generate for this product">
                </div>
                <button type="button" class="btn btn-primary mb-3" id="generateInputs">Generate</button>
            </div>

            <div class="col-md-12" id="dynamicInputs"></div>

        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div>


<script>
    // Set CSRF token globally for AJAX requests
    function get_category_id(category_id) {
        $.ajax({
            url: @json(route('product.get_has_collection')), // Fix route usage  
            type: "GET",
            data: { category_id: category_id },
            success: function (response) {
                console.log(response); // Debugging
                if (response.status === "success" && response.category) {  
                    if (response.category.has_collection == 0) {
                        $('#collection_div').hide();
                    } else {
                        $('#collection_div').show();
                    }
                } else {
                    console.log("Category not found or invalid response");
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText); // Log the actual error message
            }
        });
    }



    document.getElementById('generateInputs').addEventListener('click', function() {
        const inputCount = parseInt(document.getElementById('inputCount').value);
        const dynamicInputsContainer = document.getElementById('dynamicInputs');
        dynamicInputsContainer.innerHTML = ''; // Clear previous inputs

        if (!isNaN(inputCount) && inputCount > 0) {
            for (let i = 1; i <= inputCount; i++) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'd-flex align-items-center mb-3 p-2 bg-light rounded shadow-sm';
                rowDiv.setAttribute('data-index', i);

                const index = document.createElement('div');
                index.className = 'fw-bold me-3';
                index.innerText = `${i}.`;

                const titleInput = document.createElement('input');
                titleInput.type = 'text';
                titleInput.className = 'form-control me-3';
                titleInput.name = `collection_title[]`;
                titleInput.placeholder = `Collection ${i} Title`;

                const priceInput = document.createElement('input');
                priceInput.type = 'number';
                priceInput.className = 'form-control me-3';
                priceInput.name = `collection_price[]`;
                priceInput.placeholder = `Collection ${i} Price`;

                const removeBtn = document.createElement('button');
                removeBtn.className = 'btn btn-danger btn-sm';
                removeBtn.innerHTML = '<i class="fas fa-trash"></i>';
                removeBtn.type = 'button';

                removeBtn.addEventListener('click', function() {
                    rowDiv.remove();
                });

                rowDiv.appendChild(index);
                rowDiv.appendChild(titleInput);
                rowDiv.appendChild(priceInput);
                rowDiv.appendChild(removeBtn);

                dynamicInputsContainer.appendChild(rowDiv);
            }
        }
    });
</script>