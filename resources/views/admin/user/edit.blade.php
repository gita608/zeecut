<div class="container p-2">
    <form action="{{ route('user.update', $edit_data->id) }}" method="post">
        @csrf
        
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="{{ $edit_data->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="number" class="form-control" name="phone" value="{{ $edit_data->phone }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ $edit_data->email }}" required>
        </div>

        <button type="submit" class="btn btn-success float-end">Update</button>
    </form>
</div>
