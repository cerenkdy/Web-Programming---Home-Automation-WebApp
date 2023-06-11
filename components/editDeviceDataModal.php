<?php
if(!isset($device_group)) {
    header('Location: producer.php');
    exit;
}
?>
<div class="modal fade" id="editDeviceDataModal" tabindex="-1" aria-labelledby="editDeviceDataModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form class="modal-content">
            <input type="hidden" name="device_id" id="editDeviceId" value="0">
            <div class="modal-header">
                <h5>Edit Device Properties</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Loading...</div>
            <div class="modal-footer">
                <button class="btn btn-sh btn-sm" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>