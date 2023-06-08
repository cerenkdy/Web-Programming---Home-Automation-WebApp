<?php
if(!isset($device_group)) {
    header('Location: myhome.php');
    exit;
}
?>

<div class="modal fade" id="historyModal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">History</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled mb-0">
                    <?php
                    $stmt = $db->prepare("SELECT * FROM logs INNER JOIN devices ON logs.device_id = devices.id WHERE devices.user_id = ? ORDER BY logs.id DESC LIMIT 100");
                    $stmt->execute([$user_id]);
                    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($logs as $log) {
                        $device_name = $log['name'];
                        $device_type = $log['type'];
                        $device_status = $log['status'];
                        $log_action = $log['action'];
                        $log_user_id = $log['user_id'];
                        $log_table = $log['user_type'] =='producers' ? 'producers' : 'consumers';
                        if ($log_action == 1) {
                            $log_action_text = "turned on";
                        } else {
                            $log_action_text = "turned off";
                        }
                        if(!isset($logs_user[$log_table][$log_user_id])) {
                            $stmt = $db->prepare("SELECT * FROM consumers WHERE id = ?");
                            $stmt->execute([$log_user_id]);
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                            $logs_user[$log_table][$log_user_id] = $user['name'];
                        }
                        ?>

                    <li class="d-flex mb-2 align-items-start">
                        <span><i class="fas fa-circle fa-sm me-2 text-<?php echo ($log_action == 1) ? "success" : "danger"; ?>"></i></span>
                        <div class="d-flex flex-column justify-content-center">
                            <strong><?php echo $device_name; ?></strong>
                            <div><?php echo $log_action_text; ?> by <span class="text-muted"><?php echo $logs_user[$log_table][$log_user_id]; ?></span></div>
                        </div>
                        <date class="ms-auto text-secondary"><?php echo diffForHumans($log['created_at']); ?></date>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>