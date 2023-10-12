<div class="modal" id="editAccountTypeModal<?php echo $account_type_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-fw fa-balance-scale mr-2"></i>Editing account type: <strong><?php echo $account_type_name; ?></strong></h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="post.php" method="post" autocomplete="off">
                <input type="hidden" name="account_type_id" value="<?php echo $account_type_id; ?>">
                <div class="modal-body bg-white">

                    <div>
                        <label>Type Group</label>
                        <input type="text" class="form-control" name="type_group" value="<?php echo $account_type_group; ?>" readonly>

                    </div>
                    <div class="form-group">
                        <label>Name <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" name="name" value="<?php echo $account_type_name; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" placeholder="Description"><?php echo $account_type_description; ?></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_account_type" class="btn btn-primary text-bold"><i class="fas fa-check mr-2"></i>Save</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>