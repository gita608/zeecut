<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">{{ $page_title ?? 'Categories' }}</h5>
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                            onclick="show_small_modal('{{ route('banner.add') }}', 'Add {{ $page_title ?? 'Banner' }}')">
                            <i class="fas fa-plus"></i> Add {{ $page_title ?? 'Banner' }}
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="table1" class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Created on</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_items as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        <img src="{{asset('storage/' . $item->image)}}" alt="">
                                    </td>
                                    <td>{{ $item->created_at ? date('d-m-Y', strtotime($item->created_at)) : '—' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-warning"
                                                onclick="show_small_modal('{{ route('banner.edit', $item->id) }}', 'Edit {{ $page_title ?? 'Banner' }}')"
                                                data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="delete_modal('{{ route('banner.delete', $item->id) }}')"
                                                data-bs-toggle="tooltip" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
