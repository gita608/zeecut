<div class="container p-4">
    <form action="{{ route('product.submit') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">

            <div class="col-md-12">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-select" name="category" id="category" onchange="get_category_id(this.value)" required>
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
                <textarea class="form-control" name="description" rows="4" placeholder="Enter product description" required></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Price/kg <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" placeholder="Enter Price Per Kg" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Discount Price</label>
                <input type="number" name="discount_price" class="form-control" placeholder="Enter Discount Price">
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
                    <input type="number" id="inputCount" name="no_of_collection" class="form-control me-2" placeholder="Enter number of collections">
                    <button type="button" class="btn btn-outline-primary" id="generateInputs">Generate</button>
                </div>
                <div id="dynamicInputs"></div>
            </div>

            {{-- Image Upload Fields --}}
            <div class="col-md-12 mt-4">
                <label class="form-label">Additional Images</label>
                <button type="button" class="btn btn-outline-secondary btn-sm mb-2" id="addImage">Add Image Field</button>
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
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

    // AJAX: Show/hide collection section
    function get_category_id(category_id) {
        $.ajax({
            url: @json(route('product.get_has_collection')),
            type: "GET",
            data: { category_id },
            success: function (response) {
                if (response.status === "success" && response.category) {
                    $('#collection_div').toggle(response.category.has_collection == 1);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    // Generate Collection Fields
    document.getElementById('generateInputs').addEventListener('click', function () {
        const count = parseInt(document.getElementById('inputCount').value);
        const container = document.getElementById('dynamicInputs');
        container.innerHTML = '';

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
                        <div class="col-md-6">
                            <input type="text" name="collection_title[]" class="form-control" placeholder="Title">
                        </div>
                        <div class="col-md-6">
                            <input type="number" name="collection_price[]" class="form-control" placeholder="Price">
                        </div>
                    </div>
                `;
                container.appendChild(row);

                row.querySelector('.remove-btn').addEventListener('click', () => row.remove());
            }
        }
    });

    // Add Image Fields
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
    function validateFileSize(input, message = 'Image size should not exceed 2MB.') {
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

    // Validate thumbnail on change
    document.querySelector('input[name="thumbnail"]').addEventListener('change', function () {
        validateFileSize(this);
    });

    // Validate each dynamically added image on change
    document.addEventListener('change', function (e) {
        if (e.target && e.target.name === 'extra_images[]') {
            validateFileSize(e.target);
        }
    });

    // Full validation before submit
    document.querySelector('form').addEventListener('submit', function (e) {
        let hasError = false;

        // Validate collections
        const collectionDiv = document.getElementById('collection_div');
        if (collectionDiv && collectionDiv.style.display !== 'none') {
            const titles = document.getElementsByName('collection_title[]');
            const prices = document.getElementsByName('collection_price[]');

            for (let i = 0; i < titles.length; i++) {
                if (titles[i].value.trim() === '') {
                    titles[i].classList.add('is-invalid');
                    hasError = true;
                } else {
                    titles[i].classList.remove('is-invalid');
                }

                if (prices[i].value.trim() === '') {
                    prices[i].classList.add('is-invalid');
                    hasError = true;
                } else {
                    prices[i].classList.remove('is-invalid');
                }
            }
        }

        // Validate file sizes
        const thumbnail = document.querySelector('input[name="thumbnail"]');
        if (thumbnail && !validateFileSize(thumbnail)) {
            hasError = true;
        }

        const extraImages = document.getElementsByName('extra_images[]');
        for (let i = 0; i < extraImages.length; i++) {
            if (!validateFileSize(extraImages[i])) {
                hasError = true;
            }
        }

        if (hasError) {
            e.preventDefault();
        }
    });
</script>
