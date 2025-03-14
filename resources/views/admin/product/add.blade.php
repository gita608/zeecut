<div class="container p-2">
    <form action="{{route('product.submit')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                    <select class="form-control" name="category" id="category">
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
                    <label class="form-label" for="price">Price <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" id="price" placeholder="Enter price"
                        required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="discount_price">Discount Price </label>
                    <input type="number" name="discount_price" class="form-control" id="discount_price"
                        placeholder="Enter Discount price">
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="thumbnail">Thumbnail <span class="text-danger">*</span></label>
                    <input type="file" name="thumbnail" class="form-control" id="thumbnail" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="inputCount">Number of Collection</label>
                    <input type="number" id="inputCount" name="no_of_collection" class="form-control"
                        placeholder="Enter number of Collection to genarate for this product">
                </div>
                <button type="button" class="btn btn-primary mb-3" id="generateInputs">Generate</button>
            </div>

            <div class="col-md-12" id="dynamicInputs" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>

        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div>

<script>
    document.getElementById('generateInputs').addEventListener('click', function() {
    const inputCount = parseInt(document.getElementById('inputCount').value);
    const dynamicInputsContainer = document.getElementById('dynamicInputs');
    dynamicInputsContainer.innerHTML = ''; // Clear previous inputs

    if (!isNaN(inputCount) && inputCount > 0) {
        for (let i = 1; i <= inputCount; i++) {
            const groupDiv = document.createElement('div');
            groupDiv.className = 'input-group mb-3';
            groupDiv.style = 'flex: 1 1 48%;';

            const titleInput = document.createElement('input');
            titleInput.type = 'text';
            titleInput.className = 'form-control';
            titleInput.name = `collection_title[]`;
            titleInput.placeholder = `Collection ${i} Title`;

            const priceInput = document.createElement('input');
            priceInput.type = 'number';
            priceInput.className = 'form-control';
            priceInput.name = `collection_price[]`;
            priceInput.placeholder = `Collection ${i} Price`;

            groupDiv.appendChild(titleInput);
            groupDiv.appendChild(priceInput);
            dynamicInputsContainer.appendChild(groupDiv);
        }
    }
});


</script>