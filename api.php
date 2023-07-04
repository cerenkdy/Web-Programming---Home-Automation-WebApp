<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// json response
header('Content-Type: application/json');

$user = intval($_SESSION['user']);
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'getSongs':
        $data = [
            [
                "cover" => "https://i.scdn.co/image/ab67616d0000b273b6d4566db0d12894a1a3b7a2",
                "title" => "Undisclosed Desires",
                "artist" => "Muse",
                "album" => "The Resistance",
                "duration" => "3:56",
                "url" => "https://open.spotify.com/track/3K4HG9evC7dg3N0R9cYqk4"
            ], [
                "cover" => "https://i.scdn.co/image/ab67616d0000485129b3f99acf4ee06bfa44fa54",
                "title" => "September Song",
                "artist" => "Anges Obel",
                "album" => "Aventine",
                "duration" => "3:15",
                "url" => "https://open.spotify.com/track/2EuqgpA1cTC95AQUgCcZHk"
            ], [
                "cover" => "https://i.scdn.co/image/ab67616d000048516142f1d46f6d8b804382fb25",
                "title" => "Familiar",
                "artist" => "Agnes Obel",
                "album" => "Citizen of Glass",
                "duration" => "3:50",
                "url" => "https://open.spotify.com/track/2EWnKuspetOzgfBtmaNZvJ"
            ], [
                "cover" => "https://i.scdn.co/image/ab67616d00004851a807936f0595920c2fdf2130",
                "title" => "Lost Day",
                "artist" => "Other Lives",
                "album" => "For Their Love",
                "duration" => "4:02",
                "url" => "https://open.spotify.com/track/50XHvARYO6Sz0bxvOOD6oE"
            ], [
                "cover" => "https://i.scdn.co/image/ab67616d00004851aaeb5c9fb6131977995b7f0e",
                "title" => "Sweden",
                "artist" => "C418",
                "album" => "Minecraft",
                "duration" => "3:35",
                "url" => "https://open.spotify.com/track/4NsPgRYUdHu2Q5JRNgXYU5"
            ]
        ];
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
        break;
    case 'getUsagesData':
        $stmt = $db->prepare("SELECT data FROM rooms WHERE user_id = ?");
        $stmt->execute([$user]);
        $temperatures = [];
        $humidities = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data = json_decode($row['data'], true);
            if(isset($data['temperature']) && $data['temperature'] > 0 && isset($data['temperature_status']) && $data['temperature_status'] == '1') {
                $temperatures[] = $data['temperature'];
            }
            if(isset($data['humidity']) && $data['humidity'] > 0 && isset($data['humidity_status']) && $data['humidity_status'] == '1') {
                $humidities[] = $data['humidity'];
            }
        }

        $stmt = $db->prepare("SELECT COUNT(*) AS total FROM devices WHERE user_id = ? AND status = 1 AND electricity = 1");
        $stmt->execute([$user]);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo json_encode([
            'status' => 'success',
            'electricity' => $total . ' KWh',
            'water' => rand(1,5) . ' L',
            'gas' => rand(1,5) . ' m<sup>3</sup>',
            'temperature' => count($temperatures) ? round(array_sum($temperatures) / count($temperatures), 1) : 0,
            'humidity' => count($humidities) ? round(array_sum($humidities) / count($humidities), 1) : 0
        ]);
        break;
    case 'getConsumptionData':
        $dataElectric = [];
        $dataGas = [];
        $dataWater = [];

        if(!isset($_GET['period'])) {
            $_GET['period'] = 'all';
        }
        switch ($_GET['period']) {
            case 'today':
            case 'yesterday':
                $labels = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', 
                    '07:00', '08:00', '09:00', '10:00', '11:00', '12:00',
                    '13:00', '14:00', '15:00', '16:00', '17:00', '18:00',
                    '19:00', '20:00', '21:00', '22:00', '23:00'];
                for ($i = 0; $i < 24; $i++) {
                    $dataElectric[] = rand(1, 10);
                    $dataWater[] = rand(1, 10);
                    $dataGas[] = rand(1, 10);
                }
                break;
            case 'week':
                $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                for ($i = 0; $i < 7; $i++) {
                    $dataElectric[] = rand(10, 100);
                    $dataWater[] = rand(10, 100);
                    $dataGas[] = rand(10, 100);
                }
                break;
            case 'month':
                $labels = range(1, 30);
                for ($i = 0; $i < 30; $i++) {
                    $dataElectric[] = rand(50, 250);
                    $dataWater[] = rand(50, 250);
                    $dataGas[] = rand(50, 250);
                }
                break;
            default:
                $_GET['period'] = 'all';
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $dataElectric = [rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500)];
                $dataWater = [rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500)];
                $dataGas = [rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500), rand(100, 500)];
                break;
            }

        $checkSensorData = $db->prepare("SELECT COUNT(*) AS total FROM sensor_data WHERE user_id = ?");
        $checkSensorData->execute([$user]);
        $checkSensorData = $checkSensorData->fetch(PDO::FETCH_ASSOC)['total'];
        if ($checkSensorData == 0) {
            $dataElectric = [];
            $dataGas = [];
            $dataWater = [];
        }

        $data = [
            [
                'label' => 'Electric',
                'data' => $dataElectric,
                'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                'borderColor' => 'rgba(255, 206, 86, 1)',
                'borderWidth' => 2,
                'fill' => false,
                'pointRadius' => 0,
                'pointHoverRadius' => 0,
                'pointHitRadius' => 0,
                'pointBorderWidth' => 0,
                'pointStyle' => 'rectRounded'
            ], [
                'label' => 'Water',
                'data' => $dataWater,
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 2,
                'fill' => false,
                'pointRadius' => 0,
                'pointHoverRadius' => 0,
                'pointHitRadius' => 0,
                'pointBorderWidth' => 0,
                'pointStyle' => 'rectRounded'
            ], [
                'label' => 'Gas',
                'data' => $dataGas,
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 2,
                'fill' => false,
                'pointRadius' => 0,
                'pointHoverRadius' => 0,
                'pointHitRadius' => 0,
                'pointBorderWidth' => 0,
                'pointStyle' => 'rectRounded'
            ]
        ];
        echo json_encode([
            'status' => 'success',
            'data' => $data,
            'labels' => $labels
        ]);
        break;
    case 'setRoomData':
        $id = intval($_POST['id']);
        $config = $_POST['config'];
        $value = $_POST['value'];

        // check if temperature value is valid
        if($config == 'temperature' && ($value < 18 || $value > 30)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Temperature must be between 18 and 30'
            ]);
        }

        // check if humidity value is valid
        if($config == 'humidity' && ($value < 30 || $value > 80)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Humidity must be between 30 and 80'
            ]);
            exit;
        }

        // get config
        $stmt = $db->prepare("SELECT data FROM rooms WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = json_decode($data["data"], true);

        
        // set room data
        $data[$config] = $value;
        $stmt = $db->prepare("UPDATE rooms SET data = ? WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([json_encode($data), $id, $user]);
        $result = $stmt ? 'success' : 'error';

        echo json_encode([
            'status' => $result
        ]);
        break;
    case 'setDeviceStatus':
        $id = intval($_POST['id']);
        $stmt = $db->prepare("SELECT * FROM devices WHERE id = ? AND user_id = ? LIMIT 1");
        if (!$stmt->execute([$id, $user])) {
            echo json_encode([
                'status' => 'error'
            ]);
            exit;
        }

        $device = $stmt->fetch(PDO::FETCH_ASSOC);
        $device['data'] = json_decode($device['data'], true);
        $status = intval(($_POST['status']) ? '1' : '0');

        if(isset($device['data']['status'])) {
            $device['data']['status'] = $status;
        }

        $stmt = $db->prepare("UPDATE devices SET status = ?, data = ? WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$status, json_encode($device['data']), $id, $user]);

        $result = $stmt ? 'success' : 'error';
        
        if ($result == 'success') {
            $stmt = $db->prepare("INSERT INTO logs (user_id, user_type, device_id, action) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user, $_SESSION['type'], $id, $status]);
        }

        echo json_encode([
            'status' => $result
        ]);
        break;
    case 'setDeviceData':
        $id = intval($_POST['id']);
        $config = $_POST['config'];
        $value = $_POST['value'];

        // get config
        $stmt = $db->prepare("SELECT type, data FROM devices WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user]);
        $device = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = @json_decode($device["data"], true);

        // set config
        if (isset($data[$config]) || isset($device_groups[$device['type']][$config])) {
            $data[$config] = $value;
            $stmt = $db->prepare("UPDATE devices SET data = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([json_encode($data), $id, $user]);
            $result = $stmt ? 'success' : 'error';
        } else {
            $result = 'error';
        }
        echo json_encode([
            'status' => $result
        ]);
        break;
    case 'setConfig':
        $condition = (isset($_POST['name']) && is_numeric($_POST['name']) && $_POST['name'] > 0) ? 'id' : 'type';
        $name = $_POST['name'];
        $config = $_POST['config'];
        $value = $_POST['value'];

        // get config
        $stmt = $db->prepare("SELECT id, data FROM home_configs WHERE " . $condition . " = ? AND user_id = ?");
        $stmt->execute([$name, $user]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $config_id = $data['id'];
        $data = @json_decode($data["data"], true);

        // set config
        if (isset($data[$config])) {
            $data[$config] = $value;
            $stmt = $db->prepare("UPDATE home_configs SET data = ? WHERE " . $condition . " = ? AND user_id = ?");
            $stmt->execute([json_encode($data), $name, $user]);
            $result = $stmt ? 'success' : 'error';
        } else {
            $result = 'error';
        }

        // add log
        if ($result == 'success' && ($value == '1' || $value == '0')) {
            $value = ($value == '1') ? '1' : '0';
            $stmt = $db->prepare("INSERT INTO logs (user_id, user_type, config_id, action) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user, $_SESSION['type'], $config_id, $value]);
        }

        echo json_encode([
            'status' => $result
        ]);
        break;
    case 'addRoom':
        $name = $_POST['roomName'];

        if(isset($_SESSION['producer_login']) && isset($_POST['consumer'])) {
            $user = intval($_POST['consumer']);
        }
        
        $roomData = [
            'temperature' => rand(15,30),
            'temperature_status' => (isset($_POST['temperature'])) ? '1' : '0',
            'humidity' => rand(30,80),
            'humidity_status' => (isset($_POST['humidity'])) ? '1' : '0',
            'fireco' => 0,
            'fireco_status' => (isset($_POST['fireco'])) ? '1' : '0'
        ];

        $stmt = $db->prepare('INSERT INTO rooms (name, user_id, data) VALUES (?, ?, ?)');
        $stmt->execute([$name, $user, json_encode($roomData)]);
        $roomID = $db->lastInsertId();
        
        if ($roomID && isset($_POST['device']) && is_array($_POST['device']) && count($_POST['device']) > 0) {
            $stmt = $db->prepare('INSERT INTO devices (name, user_id, room_id, type, data, status) VALUES (?, ?, ?, ?, ?, 1)');
            foreach ($_POST['device'] as $device) {
                $deviceData = $device_group[$device];
                if ($deviceData) {
                    $stmt->execute([$name . ' ' . $deviceData['name'], $user, $roomID, $device, json_encode($deviceData['data'])]);
                }
            }
        }
        echo json_encode([
            'status' => 'success',
            'id' => $roomID
        ]);
        break;
    case 'editRoom':
        if (isset($_POST['id']) && isset($_POST['roomName']) && $_POST['roomName'] != '') {
            
            $stmt = $db->prepare('SELECT * FROM rooms WHERE id = ? AND user_id = ?');
            $stmt->execute([$_POST['id'], $user]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($room) {
                $room['data'] = @json_decode($room['data'], true);
                if (!$room['data']) {
                    $room['data'] = [];
                }
                
                if(isset($_POST['temperature'])) {
                    if (!isset($room['data']['temperature'])) {
                        $room['data']['temperature'] = 25;
                    }
                    $room['data']['temperature_status'] = '1';
                } else {
                    $room['data']['temperature_status'] = '0';
                }
                if(isset($_POST['humidity'])) {
                    if (!isset($room['data']['humidity'])) {
                        $room['data']['humidity'] = 50;
                    }
                    $room['data']['humidity_status'] = '1';
                } else {
                    $room['data']['humidity_status'] = '0';
                }
                if(isset($_POST['fireco'])) {
                    if (!isset($room['data']['fireco'])) {
                        $room['data']['fireco'] = 0;
                    }
                    $room['data']['fireco_status'] = '1';
                } else {
                    $room['data']['fireco_status'] = '0';
                }

                $name = $_POST['roomName'];
                $stmt = $db->prepare('UPDATE rooms SET name = ?, data = ? WHERE id = ? AND user_id = ?');
                $stmt->execute([$name, json_encode($room['data']), $_POST['id'], $user]);
                echo json_encode([
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid room id'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid room name'
            ]);
        }
        break;
    case 'deleteRoom':
        // delete sensor data
        $db->prepare('DELETE FROM sensor_data WHERE room_id = ? AND user_id = ?')->execute([$_POST['id'], $user]);
        
        // delete device logs each devices
        $stmt = $db->prepare('SELECT id FROM devices WHERE room_id = ? AND user_id = ?');
        $stmt->execute([$_POST['id'], $user]);
        $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($devices) {
            $stmt = $db->prepare('DELETE FROM logs WHERE device_id = ?');
            foreach ($devices as $device) {
                $stmt->execute([$device['id']]);
            }
        }
        $stmt = $db->prepare('DELETE FROM devices WHERE room_id = ? AND user_id = ?');
        $stmt->execute([$_POST['id'], $user]);
        $stmt = $db->prepare('DELETE FROM rooms WHERE id = ? AND user_id = ?');
        $stmt->execute([$_POST['id'], $user]);
        echo json_encode([
            'status' => 'success'
        ]);
        break;
    case 'getRoom':
        $stmt = $db->prepare('SELECT * FROM rooms WHERE id = ? AND user_id = ?');
        $stmt->execute([$_POST['id'], $user]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($room) {
            $stmt = $db->prepare('SELECT * FROM devices WHERE room_id = ? AND user_id = ?');
            $stmt->execute([$_POST['id'], $user]);
            $room['data'] = @json_decode($room['data'], true);
            echo json_encode([
                'status' => 'success',
                'data' => $room
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Room not found'
            ]);
        }
        break;
    case 'addDevice':
        if (isset($_POST['type']) && isset($device_group[$_POST['type']]) && !empty($_POST['name']) && !empty($_POST['room_id'])) {
            $name = $_POST['name'];
            $type = $_POST['type'];
            $room_id = $_POST['room_id'];
            $status = isset($_POST['status']) ? '1' : '0';
            $device = $device_group[$type];

            $stmt = $db->prepare("INSERT INTO devices (user_id, room_id, name, type, status, electricity, data) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user, $room_id, $name, $type, $status, $device['electricity'], json_encode($device['data'])]);
            $result = $stmt ? 'success' : 'error';

            echo json_encode([
                'status' => $result
            ]);
        }

        break;
    case 'getDevice':
        $id = intval($_POST['id']);
        $stmt = $db->prepare("SELECT * FROM devices WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$id, $user]);
        $device = $stmt->fetch(PDO::FETCH_ASSOC);
        $device['data'] = json_decode($device['data'], true);
        echo json_encode([
            'status' => 'success',
            'device' => $device
        ]);
        break;
    case 'deleteDevice':
        $id = intval($_POST['id']);

        // delete device logs
        $stmt = $db->prepare("DELETE FROM logs WHERE device_id = ? AND user_id = ?");
        $stmt->execute([$id, $user]);

        // delete device
        $stmt = $db->prepare("DELETE FROM devices WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$id, $user]);
        
        $result = $stmt ? 'success' : 'error';
        echo json_encode([
            'status' => $result
        ]);
        break;
    case 'getLamp':
        $id = intval($_POST['id']);
        $stmt = $db->prepare("SELECT * FROM devices WHERE type = 'light' AND id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$id, $user]);
        $device = $stmt->fetch(PDO::FETCH_ASSOC);
        $device['data'] = json_decode($device['data'], true);
        echo json_encode([
            'status' => 'success',
            'device' => $device
        ]);
        break;
    case 'addLamp':
        $room_id = $_POST['id'];
        $name = $_POST['name'];
        $color = $_POST['color'];
        $brightness = $_POST['brightness'];
        $status = $_POST['status'];

        $stmt = $db->prepare("INSERT INTO devices (user_id, room_id, name, type, status, electricity, data) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user, $room_id, $name, 'light', $status, 1, json_encode(['status' => $status, 'color' => $color, 'brightness' => $brightness])]);
        $result = $stmt ? 'success' : 'error';

        echo json_encode([
            'status' => $result
        ]);
        break;
    case 'editLamp':
        $id = intval($_POST['id']);
        $name = $_POST['name'];
        $data = [
            'color' => $_POST['color'],
            'brightness' => $_POST['brightness']
        ];

        $stmt = $db->prepare("UPDATE devices SET name = ?, data = ? WHERE id = ? AND user_id = ? LIMIT 1");

        $stmt->execute([$name, json_encode($data), $id, $user]);
        $result = $stmt ? 'success' : 'error';

        echo json_encode([
            'status' => $result
        ]);
        break;
    case 'deleteLamp':
        $id = intval($_POST['id']);

        // delete device logs
        $stmt = $db->prepare("DELETE FROM logs WHERE device_id = ? AND user_id = ?");
        $stmt->execute([$id, $user]);

        // delete device
        $stmt = $db->prepare("DELETE FROM devices WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$id, $user]);
        
        $result = $stmt ? 'success' : 'error';
        echo json_encode([
            'status' => $result
        ]);
        break;
    case 'roomSensorData':
        $id = intval($_POST['id']);
        // get sensor datas
        $sensorLimit = 7;
        $sensors = [
            'temperature' => array_fill(0, $sensorLimit, 0),
            'humidity' => array_fill(0, $sensorLimit, 0),
        ];
        $stmt = $db->prepare("SELECT temperature, humidity FROM sensor_data WHERE room_id = ? AND user_id = ? ORDER BY id DESC LIMIT 7");
        $stmt->execute([$id, $user]);
        $sensorCounter = $sensorLimit - 1;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sensors['temperature'][$sensorCounter] = $row['temperature'];
            $sensors['humidity'][$sensorCounter] = $row['humidity'];
            $sensorCounter--;
        }
        echo json_encode([
            'status' => 'success',
            'sensors' => $sensors,
            'temperature' => $sensors['temperature'][$sensorLimit - 1],
            'humidity' => $sensors['humidity'][$sensorLimit - 1],
        ]);
        break;
    case 'setDeviceDataMultiple':
        $id = intval($_POST['device_id']);
        // get device
        $stmt = $db->prepare("SELECT type, status, data FROM devices WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user]);
        if (!$stmt->rowCount()) {
            echo json_encode([
                'status' => 'error'
            ]);
            break;
        }
        $device = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = @json_decode($device["data"], true);

        // update name
        $name = $_POST['name'];
        unset($_POST['name']);
        $stmt = $db->prepare("UPDATE devices SET name = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$name, $id, $user]);

        // update room_id
        $room_id = $_POST['room_id'];
        unset($_POST['room_id']);
        $stmt = $db->prepare("UPDATE devices SET room_id = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$room_id, $id, $user]);

        // check brightness
        if (isset($_POST['brightness']) && !is_numeric($_POST['brightness'])) {
            unset($_POST['brightness']);
        }

        // check channel
        if (isset($_POST['channel']) && !is_numeric($_POST['channel'])) {
            unset($_POST['channel']);
        }

        // check volume
        if (isset($_POST['volume']) && !is_numeric($_POST['volume'])) {
            unset($_POST['volume']);
        }

        // check shade
        if (isset($_POST['shade']) && !is_numeric($_POST['shade'])) {
            unset($_POST['shade']);
        }

        // update status
        $status = $device['status'];
        foreach ($_POST as $config => $value) {
            if ($config != 'device_id' && isset($data[$config]) || isset($device_groups[$device['type']][$config])) {
                if($config == 'mode') {
                    if(isset($device_groups[$device['type']]['modes'][$value])) {
                        $data[$config] = $value;
                    }
                } else {
                    $data[$config] = $value;
                    if($config == 'status') {
                        $status = $value;
                    }
                }
            }
        }
        $stmt = $db->prepare("UPDATE devices SET data = ?, status = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([json_encode($data), $status, $id, $user]);
        $result = $stmt ? 'success' : 'error';
        echo json_encode([
            'status' => $result
        ]);
        break;
    case 'getDeviceDataForm':
        $stmt = $db->prepare('SELECT * FROM devices WHERE id = ? AND user_id = ?');
        if ($stmt->execute([$_POST['id'], $user])) {
            $device = $stmt->fetch(PDO::FETCH_ASSOC);
            $device['data'] = @json_decode($device['data'], true);
            $deviceData = $device_group[$device['type']];

            $data = '<div class="form-group mb-3">
                <label for="device-name">Name</label>
                <input type="text" class="form-control" id="device-name" name="name" value="' . htmlentities($device['name']) . '">
            </div>';
            
            $data .= '<div class="mb-3">
                <label for="editDeviceRoom" class="form-label">Device Room</label>
                <select class="form-select" id="editDeviceRoom" name="room_id">
                    <option selected disabled>Select device room</option>';
            $stmt = $db->prepare("SELECT id, name FROM rooms WHERE user_id = ?");
            $stmt->execute([$user]);
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rooms as $room) {
                $room_id = intval($room['id']);
                $room_name = htmlentities($room['name']);
                $data .= '<option value="' . $room_id . '"' . ($room_id == $device['room_id'] ? ' selected' : '') . '>' . $room_name . '</option>';
            }
            $data .= '</select>
            </div>';


            if (isset($device['data']) && is_array($device['data']) && count($device['data']) > 0) {
                foreach ($device['data'] as $deviceDataKey => $deviceDataValue) {
                    $deviceDataKey = htmlentities($deviceDataKey);
                    switch ($deviceDataKey) {
                        case 'color':
                            $data .= '<div class="form-group mb-3">
                                <label for="device-data-' . $deviceDataKey . '">' . ucwords($deviceDataKey) . '</label>
                                <input type="color" class="form-control" id="device-data-' . $deviceDataKey . '" name="' . $deviceDataKey . '" value="' . htmlentities($deviceDataValue) . '">
                            </div>';
                                break;
                        case 'brightness':
                        case 'shade':
                            $data .= '<div class="mb-3">
                                <label for="device-data-' . $deviceDataKey . '">' . ucwords($deviceDataKey) . '</label>
                                <input type="range" class="form-range" id="device-data-' . $deviceDataKey . '" name="' . $deviceDataKey . '" value="' . htmlentities($deviceDataValue) . '" min="0" max="100">
                            </div>';
                            break;
                        case 'status':
                            $data .= '<div class="form-group mb-3">
                                <label for="device-data-' . $deviceDataKey . '">' . ucwords($deviceDataKey) . '</label>
                                <select class="form-control" id="device-data-' . $deviceDataKey . '" name="' . $deviceDataKey . '">
                                    <option value="1" ' . ($deviceDataValue == 1 ? 'selected' : '') . '>On</option>
                                    <option value="0" ' . ($deviceDataValue == 0 ? 'selected' : '') . '>Off</option>
                                </select>
                            </div>';
                            break;
                        case 'mode':
                            if (!isset($deviceData['modes'])) {
                                break;
                            }
                            $data .= '<div class="form-group mb-3">
                                <label for="device-data-' . $deviceDataKey . '">' . ucwords($deviceDataKey) . '</label>
                                <select class="form-control" id="device-data-' . $deviceDataKey . '" name="' . $deviceDataKey . '">';
                            foreach($deviceData['modes'] as $mode => $modeName) {
                                $data .= '<option value="' . $mode . '" ' . ($deviceDataValue == $mode ? 'selected' : '') . '>' . $modeName['name'] . '</option>';
                            }
                            $data .= '</select>
                            </div>';
                            break;
                        default:
                            $data .= '<div class="form-group mb-3">
                                <label for="device-data-' . $deviceDataKey . '">' . ucwords($deviceDataKey) . '</label>
                                <input type="text" class="form-control" id="device-data-' . $deviceDataKey . '" name="' . $deviceDataKey . '" value="' . htmlentities($deviceDataValue) . '">
                            </div>';
                            break;
                    }
                }
            }

            $name = $device['name'];
            if ($deviceData['name'] != $name) {
                $name = $deviceData['name'] . ' - ' . $name;
            }

            echo json_encode([
                'status' => 'success',
                'data' => $data,
                'name' => $name
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Device not found'
            ]);
        }
        break;
}
?>