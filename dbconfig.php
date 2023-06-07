<?php
// application config
$name = 'Smart Home';


// database connection config
$dbhost = '127.0.0.1';
$dbuser = 'root';
$dbpass = '';
$dbname = 'smarthome';

// devices config
$device_group = [
    'light' => [
        'name' => 'Light',
        'short' => 'Lamp',
        'icon' => 'fas fa-lightbulb',
        'electricity' => true,
        'data' => [
            'status' => 1,
            'color' => '#ffffff',
            'brightness' => 100
        ]
    ],
    'ac' => [
        'name' => 'Air Conditioner',
        'short' => 'AC',
        'icon' => 'fas fa-snowflake',
        'electricity' => true,
        'data' => [
            'status' => 1,
            'temperature' => 25
        ]
    ],
    'tv' => [
        'name' => 'TV',
        'short' => 'TV',
        'icon' => 'fas fa-tv',
        'electricity' => true,
        'data' => [
            'status' => 1,
            'channel' => 1,
            'volume' => 50
        ]
    ],
    'fan' => [
        'name' => 'Fan',
        'short' => 'Fan',
        'icon' => 'fas fa-fan',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
    'refrigerator' => [
        'name' => 'Refrigerator',
        'short' => 'Fridge',
        'icon' => 'fas fa-ice-cream',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
    'washing_machine' => [
        'name' => 'Washing Machine',
        'short' => 'Washer',
        'icon' => 'fas fa-tint',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
    'water_heater' => [
        'name' => 'Water Heater',
        'short' => 'Heater',
        'icon' => 'fas fa-fire',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
    'microwave' => [
        'name' => 'Microwave',
        'short' => 'Microwave',
        'icon' => 'fas fa-water',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
    'oven' => [
        'name' => 'Oven',
        'short' => 'Oven',
        'icon' => 'fas fa-temperature-high',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
    'dishwasher' => [
        'name' => 'Dishwasher',
        'short' => 'Dishwasher',
        'icon' =>  'fas fa-utensils',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
    'computer' => [
        'name' => 'Computer',
        'short' => 'PC',
        'icon' => 'fas fa-desktop',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
    'router' => [
        'name' => 'Router',
        'short' => 'Router',
        'icon' => 'fas fa-wifi',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
    'speaker' => [
        'name' => 'Speaker',
        'short' => 'Speaker',
        'icon' => 'fas fa-music',
        'electricity' => true,
        'data' => [
            'status' => 1,
            'volume' => 50
        ]
    ],
    'camera' => [
        'name' => 'Camera',
        'short' => 'Cam',
        'icon' => 'fas fa-camera',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
    'door' => [
        'name' => 'Door',
        'short' => 'Door',
        'icon' => 'fas fa-door-open',
        'electricity' => false,
        'data' => [
            'status' => 1,
        ]
    ],
    'window' => [
        'name' => 'Window',
        'short' => 'Window',
        'icon' => 'fas fa-window-maximize',
        'electricity' => false,
        'data' => [
            'status' => 1,
            'shade' => 50
        ]
    ],
    'vc' => [
        'name' => 'Vacuum Cleaner',
        'short' => 'VC',
        'icon' => 'fas fa-robot',
        'electricity' => true,
        'data' => [
            'status' => 1,
        ]
    ],
];

// database connection
try {
    $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// session starting
session_start();
