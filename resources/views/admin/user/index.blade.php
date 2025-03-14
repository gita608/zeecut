<div class="page-content">
    <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0">{{ $page_title ?? '' }}</h5>
            <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                onclick="show_small_modal('{{ route('product.add') }}', 'Add {{ $page_title ?? '' }}')">
                <i class="fas fa-plus"></i> Add {{ $page_title ?? '' }}
            </a>
        </div>
    </div>
    <!-- Filter Form -->
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form action="{{ route('user.index') }}" method="GET">
                <div class="row g-2">

                    <div class="col-md-4">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>

        <div class="card-body">


            <div class="table-responsive">
                <table id="table1" class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Created on</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($list_items))
                        @foreach ($list_items as $key => $item)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->created_at ? date('d-m-Y', strtotime($item->created_at )) : '' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-warning"
                                        onclick="show_small_modal('{{ route('user.edit',$item->id) }}', 'Edit {{ $page_title ?? '' }}')"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                                        onclick="delete_modal('{{ route('user.delete',$item->id) }}')"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                        <i class="fas fa-trash"></i>
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