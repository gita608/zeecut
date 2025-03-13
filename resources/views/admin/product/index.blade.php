 
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">{{ $page_title ?? '' }}</h6>
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                            onclick="show_small_modal('{{ route('product.add') }}', 'Add {{ $page_title ?? '' }}')">
                            <i class="fas fa-plus"></i> Add {{ $page_title ?? '' }}
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="table1" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Discount Price</th>
                                    <th>Status</th>
                                    <th>Created on</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($list_items))
                                @foreach ($list_items as $key => $item)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        <img src="{{ asset($item->thumbnail) }}" alt="" width="130px">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category_name ?? '' }}</td>
                                    <td>{{ round($item->price) }} ₹</td>
                                    <td>{{ round($item->discount_price) }} ₹</td>
                                    <td>
                                        <input type="checkbox" class="toggle-status" value="1" onchange="get_ajax_status(this.value,{{$item->id}})" {{ $item->status ? 'checked' : '' }}>
                                    </td>                                    
                                    <td>{{ $item->created_at ? date('d-m-Y', strtotime($item->created_at )) : '' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-warning"
                                                onclick="show_small_modal('{{ route('product.edit',$item->id) }}', 'Edit {{ $page_title ?? '' }}')">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <a class="btn btn-sm btn-danger" href="javascript:void(0);"
                                                onclick="delete_modal('{{ route('product.delete',$item->id) }}')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function get_ajax_status(status,product_id){
        $.ajax({
            url: "{{ route('product.toggleStatus') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: product_id,
                status: status
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
    position: relative;
    width: 50px;
    height: 25px;
    -webkit-appearance: none;
    background: #ccc;
    outline: none;
    border-radius: 50px;
    box-shadow: inset 0 0 5px rgba(0,0,0,.2);
    transition: .4s;
    cursor: pointer;
}

.toggle-status:checked {
    background: #4cd964; /* iPhone Green */
}

.toggle-status:before {
    content: "";
    position: absolute;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    top: 1.5px;
    left: 2px;
    background: #fff;
    transition: .4s;
}

.toggle-status:checked:before {
    left: 26px;
}

</style>