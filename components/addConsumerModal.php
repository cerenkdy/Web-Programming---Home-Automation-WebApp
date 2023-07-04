<?php
if(!isset($device_group)) {
    header('Location: /producer.php');
    exit;
}
?>

<!-- Add Consumer Modal -->
<div class="modal fade" id="addConsumerModal" tabindex="-1" aria-labelledby="addConsumerModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="add_consumer" value="1">
            <div class="modal-header">
                <h5 class="modal-title" id="addConsumerModalLabel">Add Consumer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sh ms-auto" type="submit">Add</button>
            </div>
        </form>
    </div>
</div>