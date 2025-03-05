<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{$page_title}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{$page_title}}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">

                        <div>
                            <a href="javascript:void(0);" class="btn btn-light" onclick="show_small_modal('{{route('categories.add')}}', 'Add {{$page_title}}')"><i class="bx bx-plus me-1"></i> Add {{$page_title}}</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="table-responsive m-4">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Thumbnail</th>
                            <th scope="col">Title</th>
                            <th style="width: 80px; min-width: 80px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($list_items as $key => $items)
                           <tr>
                                <td>{{$key + 1}}</td>
                                <td>
                                    <img src="{{ asset('storage/'.$items->thumbnail) }}" alt="{{ $items->title }}" width="100">
                                </td>
                                <td>{{$items->title}}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-warning btn-xs" onclick="show_small_modal('{{route('categories.edit',$items->id)}}', 'Edit {{$page_title}}')">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="show_delete_modal('{{route('categories.delete',$items->id)}}')" class="btn btn-danger btn-xs">Delete</a>
                                </td>
                           </tr>
                       @endforeach

                    </tbody>
                </table>
                <!-- end table -->
            </div>
            <!-- end table responsive -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->