<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if producer is not logged in, redirect to login.php
if (!isset($_SESSION['producer_login'])) {
    header("Location: login.php");
}

// json response
header('Content-Type: application/json');

$user = intval($_SESSION['user']);
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'getRoomsDataForm':
        $stmt = $db->prepare('SELECT * FROM rooms WHERE user_id = ?');
        if ($stmt->execute([$user])) {
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = '<input type="hidden" name="user_id" value="' . $user . '"><input type="hidden" name="type" value="manual">';
            foreach ($rooms as $room) {
                $room['data'] = @json_decode($room['data'], true);
                if($room['data']['temperature_status'] != 1 && $room['data']['humidity_status'] != 1) {
                    continue;
                }
                $data .= '<div class="form-group mb-3">
                    <input type="hidden" name="rooms[' . $room['id'] . '][room_id]" value="' . $room['id'] . '">
                    <label for="room-data-' . $room['id'] . '">' . $room['name'] . '</label>
                    <div class="row">
                ';
                if($room['data']['temperature_status'] == 1) {
                    $data .= '<div class="col">
                        <div class="input-group">
                            <span class="input-group-text">Temp.</span>
                            <input type="number" class="form-control" id="room-data-' . $room['id'] . '-temperature" name="rooms[' . $room['id'] . '][temperature]" value="' . (isset($room['data']['temperature']) ? htmlentities($room['data']['temperature']) : '') . '">
                            <span class="input-group-text">°C</span>
                        </div>
                    </div>';
                }
                if($room['data']['humidity_status'] == 1) {
                    $data .= '<div class="col">
                        <div class="input-group">
                            <span class="input-group-text">Humidity</span>
                            <input type="number" class="form-control" id="room-data-' . $room['id'] . '-humidity" name="rooms[' . $room['id'] . '][humidity]" value="' . (isset($room['data']['humidity']) ? htmlentities($room['data']['humidity']) : '') . '">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>';
                }
                $data .= '    </div>
                </div>';
            }
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'status' => 'error'
            ]);
        }
        break;
    case 'sendData':
        $type = isset($_POST['type']) ? $_POST['type'] : false;
        $user_id = @intval($_POST['user_id']);
        if ($type = 'manual') {
            $stmt = $db->prepare('SELECT * FROM rooms WHERE user_id = ?');
            if ($stmt->execute([$user_id])) {
                $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($rooms as $room) {
                    $room_id = intval($room['id']);
                    $room_data = @json_decode($room['data'], true);
                    $temperature = ($room_data['temperature_status'] == 1) ? $room_data['temperature'] + rand(-2, 2) : 0;
                    $humidity = ($room_data['humidity_status'] == 1) ? $room_data['humidity'] + rand(-2, 2) : 0;
                    $stmt = $db->prepare('INSERT INTO sensor_data (user_id, room_id, temperature, humidity) VALUES (?, ?, ?, ?)');
                    $stmt->execute([$user_id, $room_id, $temperature, $humidity]);
                }
            }
        }
        if ($type = 'sensor') {
            $rooms = isset($_POST['rooms']) ? $_POST['rooms'] : [];
            if(is_array($rooms)) {
                foreach ($rooms as $room) {
                    $room_id = intval($room['room_id']);
                    $temperature = isset($room['temperature']) ? intval($room['temperature']) : 0;
                    $humidity = isset($room['humidity']) ? intval($room['humidity']) : 0;
                    $stmt = $db->prepare('SELECT * FROM rooms WHERE id = ? AND user_id = ?');
                    if ($user_id && $room_id && is_numeric($temperature) && is_numeric($humidity) && $user_id > 0 && $room_id > 0 && $temperature >= 0 && $humidity >= 0 && $temperature < 100 && $humidity < 100 && $stmt->execute([$room_id, $user_id])) {
                        $room = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($room) {
                            $stmt = $db->prepare('INSERT INTO sensor_data (user_id, room_id, temperature, humidity) VALUES (?, ?, ?, ?)');
                            $stmt->execute([$user_id, $room_id, $temperature, $humidity]);
                        }
                    }
                }
                echo json_encode([
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error'
                ]);
            }
        }
        break;
    case 'getConsumer':
        $stmt = $db->prepare('SELECT name, email, username, settings FROM consumers WHERE id = ?');
        if ($stmt->execute([$_POST['id']])) {
            $consumer = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($consumer) {
                $consumer['settings'] = @json_decode($consumer['settings'], true);
                echo json_encode([
                    'status' => 'success',
                    'consumer' => $consumer
                ]);
            } else {
                echo json_encode([
                    'status' => 'error'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error'
            ]);
        }
        break;
}
?>