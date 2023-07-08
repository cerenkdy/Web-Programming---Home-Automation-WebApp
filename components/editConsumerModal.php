<?php
if(!isset($device_group)) {
    header('Location: /producer.php');
    exit;
}
?>

<!-- Edit Consumer Modal -->
<div class="modal fade" id="editConsumerModal" tabindex="-1" aria-labelledby="editConsumerModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="edit_consumer" id="editConsumerId" value="0">
            <div class="modal-header">
                <h5 class="modal-title" id="editConsumerModalLabel">Edit Consumer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editConsumerName" class="form-label">Name</label>
                    <input type="text" id="editConsumerName" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="editConsumerEmail" class="form-label">Email</label>
                    <input type="email" id="editConsumerEmail" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="editConsumerUsername" class="form-label">Username</label>
                    <input type="text" id="editConsumerUsername" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="editConsumerPassword" class="form-label">Password</label>
                <input type="password" id="editConsumerPassword" name="password" class="form-control" >
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sh ms-auto" type="submit">Edit</button>
            </div>
        </form>
    </div>
</div>