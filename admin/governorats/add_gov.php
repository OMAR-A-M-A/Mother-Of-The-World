<div class="modal fade" id="addGovModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Governorates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="gov_functions.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Governorate Name</label>
                        <input type="text" name="governorate_name" class="form-control"
                            placeholder="Enter governorate name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Governorate Image</label>
                        <input type="file" name="governorate_image" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_gov_btn" class="btn btn-primary">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>