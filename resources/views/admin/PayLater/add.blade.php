

<div class="container p-2">
    <form action="{{route('payLater.submit')}}" method="post" enctype="multipart/form-data">
        @csrf <!-- Laravel CSRF token for security -->
        <div class="row">

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Users <span class="text-danger">*</span></label>
                    <select name="user_id" id="" class="form-control" required>
                        <option value="">Choose User</option>
                        @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Credit Limit <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Enter User Credit" name="credit_limit" required>
                </div>
            </div>

        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div>
