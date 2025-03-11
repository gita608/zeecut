

<div class="container p-2">
    <form action="{{route('user.submit')}}" method="post" enctype="multipart/form-data">
        @csrf <!-- Laravel CSRF token for security -->
        <div class="row">

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Phone</label>
                    <input type="number" name="phone" class="form-control" id="phone" placeholder="Enter Phone"  required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email"  required>
                </div>
            </div>

        </div>

        <button type="submit" class="btn btn-success float-end">Submit</button>
    </form>
</div>
