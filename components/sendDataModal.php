<?php
if(!isset($device_group)) {
    header('Location: /producer.php');
    exit;
}
?>
<div class="modal fade" id="sendDataModal" tabindex="-1" aria-labelledby="sendDataModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <h5>Send Sensor Data</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Loading...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-2" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary ms-auto" id="sendDataButton">Send</button>
            </div>
        </form>
    </div>
</div>