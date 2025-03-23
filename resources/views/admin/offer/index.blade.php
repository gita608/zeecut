<div class="page-content">
    <!-- Header Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 text-primary fw-bold">{{ $page_title ?? 'Offers' }}</h5>
            <a href="javascript:void(0);" class="btn btn-primary btn-sm px-3"
                onclick="show_small_modal('{{ route('offer.add') }}', 'Add {{ $page_title ?? '' }}')">
                <i class="fas fa-plus"></i> Add {{ $page_title ?? '' }}
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form action="{{ route('offer.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <select name="product_id" class="form-select border-0 shadow-sm">
                            <option value="">All Products</option>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id')==$product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-info shadow-sm"><i class="fas fa-filter"></i> Filter</button>
                        <a href="{{ route('offer.index') }}" class="btn btn-secondary shadow-sm"><i class="fas fa-sync-alt"></i> Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Card View -->
    @if(count($list_items) > 0)
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($list_items as $item)
        <div class="col">
            <div class="card shadow-lg border-0 rounded-3 offer-card h-100">
                <div class="ribbon bg-success text-white px-3 py-1">{{ round($item->discount_percentage) }}% OFF</div>
                <div class="card-body text-center">
                    <div class="thumbnail-container mb-3">
                        <!-- Use a dummy image if thumbnail doesn't exist -->
                        <img src="{{ $item->thumbnail ? asset($item->thumbnail) : 'https://via.placeholder.com/300x150' }}" 
                             alt="Product Image" 
                             class="img-fluid rounded shadow-sm">
                    </div>
                    <h6 class="card-title fw-bold text-dark mb-2">{{ $item->product_name ?? '' }}</h6>

                   <div class="d-flex justify-content-center">
                        <p class=" small m-3 fw-bold"><i class="far fa-calendar-alt"></i> Start: {{ date('d-m-Y', strtotime($item->start_date)) }}</p>

                        <p class=" small m-3 fw-bold"><i class="far fa-calendar-alt"></i> End: {{ date('d-m-Y', strtotime($item->end_date)) }}</p>
                   </div>

                    <div class="d-flex justify-content-center gap-2">
                        <a href="javascript:void(0);" class="btn btn-warning btn-sm shadow-sm px-3"
                            onclick="show_small_modal('{{ route('offer.edit', $item->id) }}', 'Edit {{ $page_title ?? '' }}')"
                            title="Edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm shadow-sm px-3"
                            onclick="delete_modal('{{ route('offer.delete', $item->id) }}')" title="Delete">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </div><hr>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center mt-4">
        <p class="text-muted">No offers available.</p>
    </div>
    @endif
</div>

<!-- Custom Styles -->
<style>
    .thumbnail-container {
        width: 100%;
        height: 150px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        border-radius: 8px;
        background-color: #f8f9fa; /* Light background for the thumbnail container */
    }

    .thumbnail-container img {
        width: 80%;
        height: auto;
        border-radius: 8px;
        object-fit: cover;
    }

    .offer-card {
        transition: transform 0.3s ease-in-out;
        position: relative;
        border: 1px solid rgba(0, 0, 0, 0.1); /* Subtle border */
    }

    .offer-card:hover {
        transform: translateY(-5px); /* Slight lift on hover */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Enhanced shadow on hover */
    }

    .ribbon {
        position: absolute;
        top: -8px;
        right: -8px;
        font-size: 12px;
        font-weight: bold;
        border-radius: 8px 0 0 8px;
        padding: 5px 12px;
        background-color: #28a745; /* Green background */
        color: white;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }

    .btn-primary, .btn-info, .btn-secondary, .btn-warning, .btn-danger {
        transition: all 0.3s ease-in-out;
    }

    .btn-primary:hover, .btn-info:hover, .btn-secondary:hover, .btn-warning:hover, .btn-danger:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
</style>