<div class="container p-4">
    <form action="{{ route('product.submit') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">

            <div class="col-md-12">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-select" name="category" id="category" onchange="get_category_id(this.value)"
                    required>
                    <option value="">Choose Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title" placeholder="Enter Title" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" name="description" rows="4" placeholder="Enter product description"
                    required></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Unit<span class="text-danger">*</span></label>
                <select name="unit" class="form-control" onchange="get_price_label(this.value)" required>
                    <option value="">Choose Unit</option>
                    <option value="1" selected>Kg</option>
                    <option value="2">Liter</option>
                    <option value="3">Quantity</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label" id="price_label">Price (Per Kg) <span class="text-danger">*</span></label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Discount Price</label>
                <input type="number" name="discount_price" id="discount_price" class="form-control"
                    placeholder="Enter Discount Price">
            </div>

            <div class="col-md-12">
                <label class="form-label">Primary Image <span class="text-danger">*</span></label>
                <input type="file" name="thumbnail" class="form-control" required>
                <small class="form-text text-muted">Max upload size: 2MB</small>
            </div>

            {{-- Collection Fields --}}
            <div class="col-md-12" id="collection_div" style="display: none;">
                <label class="form-label">Number of Collections</label>
                <div class="d-flex mb-2">
                    <input type="number" id="inputCount" name="no_of_collection" class="form-control me-2"
                        placeholder="Enter number of collections">
                    <button type="button" class="btn btn-outline-primary" id="generateInputs">Generate</button>
                </div>
                <div id="dynamicInputs"></div>
            </div>

            {{-- Image Upload Fields --}}
            <div class="col-md-12 mt-4">
                <label class="form-label">Additional Images</label>
                <button type="button" class="btn btn-outline-secondary btn-sm mb-2" id="addImage">Add Image
                    Field</button>
                <div id="imageInputs"></div>
                <small class="form-text text-muted">Each image max size: 2MB</small>
            </div>

            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-success">Submit Product</button>
            </div>

        </div>
    </form>
</div>

<style>
    .is-invalid {
        border: 1px solid red !important;
    }
</style>

<script>
    function get_price_label(value){
        if(value == 1){
            $('#price_label').html('Price (Per Kg) <span class="text-danger">*</span>');
        } else if(value == 2){
            $('#price_label').html('Price (Per Liter) <span class="text-danger">*</span>');
        } else {
            $('#price_label').html('Price (Per Quantity) <span class="text-danger">*</span>');
        }
    }
    
    $(document).ready(function(){
        get_price_label(1); // default
    });
    
    $(document).ready(function () {
    function updateNormalCollection() {
        const price = $('#price').val();
        const discountPrice = $('#discount_price').val();
        
        // Update normal collection row if exists
        const normalRow = $('.normal-row');
        if (normalRow.length > 0) {
            normalRow.find('input[name="collection_price[]"]').val(price);
            normalRow.find('input[name="collection_sale_price[]"]').val(discountPrice);
        }
    }

    $('#price, #discount_price').on('input', function () {
        updateNormalCollection();
    });
});


    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
    
    function get_category_id(category_id) {
        $.ajax({
            url: @json(route('product.get_has_collection')),
            type: "GET",
            data: { category_id },
            success: function (response) {
                if (response.status === "success" && response.category) {
                    if (response.category.has_collection == 1) {
                        $('#collection_div').show();
                        generateDefaultCollectionRow();
                    } else {
                        $('#collection_div').hide();
                        $('#dynamicInputs').html('');
                    }
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }
    
    // Generate Default Normal Row (Auto filled with main price and discount)
    function generateDefaultCollectionRow() {

        
        const container = document.getElementById('dynamicInputs');
        container.innerHTML = '';
    
        const price = document.getElementById('price').value || 0;
        const discountPrice = document.getElementById('discount_price').value || 0;
    
        const row = document.createElement('div');
        row.className = 'card p-3 mb-2 shadow-sm normal-row';
        row.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <strong>Collection - Normal</strong>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <input type="text" name="collection_title[]" class="form-control" value="Normal" >
                </div>
                <div class="col-md-4">
                    <input type="number" name="collection_price[]" class="form-control" value="${price}" >
                </div>
                <div class="col-md-4">
                    <input type="number" name="collection_sale_price[]" class="form-control" value="${discountPrice}" >
                </div>
            </div>
        `;
        container.appendChild(row);
    }
    
    // Generate Dynamic Collection Fields (Manually entered)
    document.getElementById('generateInputs').addEventListener('click', function () {
        const count = parseInt(document.getElementById('inputCount').value);
        const container = document.getElementById('dynamicInputs');
    
        const normalRow = document.querySelector('.normal-row');
        container.innerHTML = '';
        if (normalRow) container.appendChild(normalRow);
    
        if (!isNaN(count) && count > 0) {
            for (let i = 1; i <= count; i++) {
                const row = document.createElement('div');
                row.className = 'card p-3 mb-2 shadow-sm';
                row.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Collection ${i}</strong>
                        <button type="button" class="btn-close btn-sm remove-btn" aria-label="Remove"></button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <input type="text" name="collection_title[]" class="form-control" placeholder="Title" required>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="collection_price[]" class="form-control" placeholder="Price" required>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="collection_sale_price[]" class="form-control" placeholder="Discount Price">
                        </div>
                    </div>
                `;
                container.appendChild(row);
    
                row.querySelector('.remove-btn').addEventListener('click', () => row.remove());
            }
        }
    });
    
    // Add Image Field
    document.getElementById('addImage').addEventListener('click', function () {
        const imageInputs = document.getElementById('imageInputs');
        const imageGroup = document.createElement('div');
        imageGroup.className = 'd-flex align-items-center mb-2';
    
        imageGroup.innerHTML = `
            <input type="file" name="extra_images[]" class="form-control me-2">
            <button type="button" class="btn btn-danger btn-sm remove-image"><i class="fas fa-trash-alt"></i></button>
        `;
    
        imageGroup.querySelector('.remove-image').addEventListener('click', () => {
            imageGroup.remove();
        });
    
        imageInputs.appendChild(imageGroup);
    });
    
    // File size validation
    function validateFileSize(input) {
        const file = input.files[0];
        if (file && file.size > MAX_FILE_SIZE) {
            input.classList.add('is-invalid');
            input.value = '';
            return false;
        } else {
            input.classList.remove('is-invalid');
            return true;
        }
    }
    
    document.querySelector('input[name="thumbnail"]').addEventListener('change', function () {
        validateFileSize(this);
    });
    
    document.addEventListener('change', function (e) {
        if (e.target && e.target.name === 'extra_images[]') {
            validateFileSize(e.target);
        }
    });
</script>