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
                "album" => "Minecraft - Volume Alpha",
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

        $stmt = $db->prepare("SELECT COUNT(*) AS total FROM devices WHERE status = 1 AND user_id = ?");
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

        // get config
        $stmt = $db->prepare("SELECT data FROM rooms WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = json_decode($data["data"], true);

        
        // set room data
        $data[$config] = $value;
        $stmt = $db->prepare("UPDATE rooms SET data = ? WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([json_encode($data), $id, $user]);
        $result = $stmt->rowCount() ? 'success' : 'error';

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

        $result = $stmt->rowCount() ? 'success' : 'error';
        
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
            $result = $stmt->rowCount() ? 'success' : 'error';
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
        $stmt = $db->prepare("SELECT data FROM home_configs WHERE " . $condition . " = ? AND user_id = ?");
        $stmt->execute([$name, $user]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = @json_decode($data["data"], true);

        // set config
        if (isset($data[$config])) {
            $data[$config] = $value;
            $stmt = $db->prepare("UPDATE home_configs SET data = ? WHERE " . $condition . " = ? AND user_id = ?");
            $stmt->execute([json_encode($data), $name, $user]);
            $result = $stmt->rowCount() ? 'success' : 'error';
        } else {
            $result = 'error';
        }
        echo json_encode([
            'status' => $result
        ]);

        break;
}