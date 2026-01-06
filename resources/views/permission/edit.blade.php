<div class="modal fade" id="editPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editPermissionForm" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Permission Name</label>
                        <input type="text"
                               name="name"
                               id="editPermissionName"
                               class="form-control"
                               required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal" style="font-size:13px">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary rounded-0" style="font-size:13px">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
