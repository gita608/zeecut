<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">{{ $page_title ?? 'Pincode' }}</h5>
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                            onclick="show_small_modal('{{ route('pincode.add') }}', 'Add {{ $page_title ?? 'Pincode' }}')">
                            <i class="fas fa-plus"></i> Add {{ $page_title ?? 'Pincode' }}
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="table1" class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Place</th>
                                    <th>Code</th>
                                    <th>Created on</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_items as  $key => $list)
                                    
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$list->name}}</td>
                                        <td>{{$list->pincode}}</td>
                                        <td>{{date('d-m-Y',strtotime($list->created_at))}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="javascript:void(0);" class="btn btn-outline-warning btn-sm"
                                                    onclick="show_small_modal('{{ route('pincode.edit',$list->id) }}', 'Edit {{ $page_title ?? '' }}')"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm"
                                                    onclick="delete_modal('{{ route('pincode.delete',$list->id) }}')" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
