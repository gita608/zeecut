<div class="page-content">
    <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0">{{ $page_title ?? '' }}</h5>
            <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                onclick="show_ajax_modal('{{ route('product.add') }}', 'Add {{ $page_title ?? '' }}')">
                <i class="fas fa-plus"></i> Add {{ $page_title ?? '' }}
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form action="{{ route('product.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-4">
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success"><i class="fas fa-filter"></i> Filter</button>
                        <a href="{{ route('product.index') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Reset</a>
                    </div>
                </div>
            </form>
        </div>
   
        <div class="card-body">
            <div class="table-responsive">
                <table id="table1" class="table table-striped table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Discount Price</th>
                            <th>Status</th>
                            <th>Collections</th>
                            <th>Created on</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_items as $key => $item)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td><img src="{{ asset('storage/' . $item->thumbnail) }}" alt="" class="img-thumbnail" width="150"></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category_name ?? '' }}</td>
                            <td>{{ format_price($item->price) }}</td>
                            <td>{{ format_price($item->discount_price) }}</td>
                            <td>
                                <input type="checkbox" class="toggle-status" onchange="get_ajax_status(this.checked, {{$item->id}})" {{ $item->status ? 'checked' : '' }}>
                            </td>
                            <td>
                                <ul>
                                    @foreach ( $item['collection_items'] as $items )
                                    
                                        <li><?=strtoupper($items['title'])?></li>

                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $item->created_at ? date('d-m-Y', strtotime($item->created_at)) : '' }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="javascript:void(0);" class="btn btn-outline-warning btn-sm"
                                        onclick="show_ajax_modal('{{ route('product.edit',$item->id) }}', 'Edit {{ $page_title ?? '' }}')"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm"
                                        onclick="delete_modal('{{ route('product.delete',$item->id) }}')" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div><hr>
                                <a href="javascript:void(0);" class="btn btn-outline-primary w-75"
                                 onclick="show_ajax_modal('{{ route('product.view_images',$item->id) }}', 'Images {{ $page_title ?? '' }}')"
                                >View Images</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function get_ajax_status(status, product_id) {
        $.ajax({
            url: "{{ route('product.toggleStatus') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: product_id,
                status: status ? 1 : 0
            },
            success: function(response) {
                console.log(response.message);
            },
            error: function(xhr) {
                alert('Something went wrong!');
            }
        });
    }
</script>

<style>
    .toggle-status {
        width: 45px;
        height: 22px;
        -webkit-appearance: none;
        background: #ddd;
        border-radius: 50px;
        position: relative;
        cursor: pointer;
        outline: none;
        transition: background 0.3s;
    }

    .toggle-status:checked {
        background: #28a745;
    }

    .toggle-status::before {
        content: "";
        width: 18px;
        height: 18px;
        position: absolute;
        top: 2px;
        left: 3px;
        background: #fff;
        border-radius: 50%;
        transition: transform 0.3s;
    }

    .toggle-status:checked::before {
        transform: translateX(22px);
    }
</style>
