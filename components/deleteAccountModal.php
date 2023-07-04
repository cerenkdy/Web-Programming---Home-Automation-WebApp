<?php
if(!isset($device_group)) {
    header('Location: /myhome.php');
    exit;
}
?>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="">
            <input type="hidden" name="account_action" value="delete">
            <div class="modal-header">
                <h5 class="modal-title">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="deleteAccountPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="deleteAccountPassword" placeholder="Password" name="password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </div>
        </form>
    </div>
</div>