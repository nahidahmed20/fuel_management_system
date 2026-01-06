<div class="modal fade" id="permissionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Permission Name</label>
                        <input type="text" name="name" class="form-control"
                               placeholder="example: user-create" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" style="border-radius:0;font-size: 13px;">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary" style="border-radius:0;font-size: 13px;">
                        Save
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
