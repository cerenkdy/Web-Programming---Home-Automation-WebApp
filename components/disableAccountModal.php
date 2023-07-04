<?php
if(!isset($device_group)) {
    header('Location: /myhome.php');
    exit;
}
?>

<!-- Disable Account Modal -->
<div class="modal fade" id="disableAccountModal">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="">
            <input type="hidden" name="account_action" value="disable">
            <div class="modal-header">
                <h5 class="modal-title">Disable Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="disableAccountPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="disableAccountPassword" placeholder="Password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="disableAccountReason" class="form-label">Reason</label>
                    <textarea class="form-control" id="disableAccountReason" rows="2" placeholder="Reason"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-danger">Disable Account</button>
            </div>
        </form>
    </div>
</div>