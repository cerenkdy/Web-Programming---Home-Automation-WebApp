<?php
if(!isset($device_group)) {
    header('Location: /myhome.php');
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
                $stmt = $db->prepare("SELECT
                logs.id, 
                logs.user_id, 
                logs.user_type, 
                logs.device_id, 
                logs.config_id, 
                logs.action, 
                logs.created_at, 
                devices.name AS device_name,
                home_configs.type AS config_name,
                home_configs.data AS config_data
                FROM logs 
                LEFT JOIN devices ON logs.device_id = devices.id 
                LEFT JOIN home_configs ON logs.config_id = home_configs.id 
                WHERE logs.user_id = ? ORDER BY logs.id DESC LIMIT 100");
                $stmt->execute([$user_id]);
                $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $logs_user = [
                    'producers' => [],
                    'consumers' => [],
                ];
                foreach ($logs as $log) {
                    if ($log['device_name'] == null && $log['config_name'] == null) {
                        continue;
                    }
                    $log_action = $log['action'];
                    if ($log['device_name'] == null) {
                        $device_name = $log['config_name'];
                        if ($device_name == 'outdoor_lock') {
                            $device_name = 'Outdoor Lock';
                        } elseif ($device_name == 'door') {
                            $device_data = @json_decode($log['config_data'], true);
                            $device_name = $device_data['name'];
                        }
                        if ($log_action == 1) {
                            $log_action_text = "locked";
                        } else {
                            $log_action_text = "unlocked";
                        }
                    } else {
                        $device_name = $log['device_name'];
                        if ($log_action == 1 ) {
                            $log_action_text = "turned on";
                        } else {
                            $log_action_text = "turned off";
                        }
                    }
                    $log_user_id = $log['user_id'];
                    if(!isset($logs_user[$log_user_id])) {
                        $stmt = $db->prepare("SELECT * FROM `consumers` WHERE id = ?");
                        $stmt->execute([$log_user_id]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        $logs_user[$log_user_id] = $user['name'];
                    }
                    ?>

                    <li class="d-flex mb-2 align-items-start">
                        <span><i class="fas fa-circle fa-sm me-2 text-<?php echo ($log_action == 1) ? "success" : "danger"; ?>"></i></span>
                        <div class="d-flex flex-column justify-content-center">
                            <strong><?php echo $device_name; ?></strong>
                            <div><?php echo $log_action_text; ?> by <span class="text-muted"><?php echo $logs_user[$log_user_id]; ?></span></div>
                        </div>
                        <date class="ms-auto text-secondary"><?php echo diffForHumans($log['created_at']); ?></date>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>