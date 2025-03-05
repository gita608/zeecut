<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">{{ $page_title ?? '' }}</h6>
                        <a href="add.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add User
                        </a>
                    </div>

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
                                            <a href="edit.php?id={{ $item->id }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="delete.php?id={{ $item->id }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">
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
