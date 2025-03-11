<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">{{ $page_title ?? '' }}</h6>
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                            onclick="show_small_modal('{{ route('category.add') }}', 'Add {{ $page_title ?? '' }}')">
                            <i class="fas fa-plus"></i> Add {{ $page_title ?? '' }}
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="table1" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
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
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->created_at ? date('d-m-Y', strtotime($item->created_at )) : '' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-warning"
                                                onclick="show_small_modal('{{ route('category.edit',$item->id) }}', 'Edit {{ $page_title ?? '' }}')">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <a class="btn btn-sm btn-danger" href="javascript:void(0);"
                                                onclick="delete_modal('{{ route('category.delete',$item->id) }}')">
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