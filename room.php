<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Living Rooms - Smart Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/app.css" rel="stylesheet">
</head>

<body>
    <main class="d-flex flex-nowrap">
        <?php include 'components/sidebar.php';?>
        <!-- Room -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2 mw-100">
            <div class="room w-100">
                <div class="d-flex align-items-center mb-3">
                    <a href="rooms.html" class="text-decoration-none text-light me-3">
                        <i class="fas fa-chevron-left fa-lg"></i>
                    </a>
                    <h4 class="flex-grow-1 mr-2 mb-0 fw-normal text-white">Living Room</h4>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-sh dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-door-open fa-lg me-2"></i>
                            <span>Living Room</span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Living Room</a></li>
                            <li><a class="dropdown-item" href="#">Bedroom</a></li>
                            <li><a class="dropdown-item" href="#">Kitchen</a></li>
                            <li><a class="dropdown-item" href="#">Bathroom</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <!-- Lamps -->
                        <div class="card bg-white-50 rounded-4 h-100 p-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-lightbulb fa-lg me-2"></i>
                                <h2 class="h5 m-0 me-2">Lamps</h2>
                                <button class="btn btn-sm btn-sh ms-auto" type="button" data-bs-toggle="modal"
                                    data-bs-target="#addLampModal">
                                    <i class="fas fa-plus fa-lg"></i>
                                </button>
                            </div>
                            <ul class="list-unstyled p-0 m-0">
                                <li class="d-flex align-items-center mt-3">
                                    <!-- Color -->
                                    <i class="fas fa-circle fa-lg me-2" style="color: #c37189;"></i>
                                    <span class="fs-5 me-2">Main Lamp</span>
                                    <button class="btn btn-sm btn-sh me-2" type="button" data-bs-toggle="modal"
                                        data-bs-target="#editLampModal">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </button>
                                    <label class="switch ms-auto">
                                        <input type="checkbox" class="apple-switch" checked>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="d-flex flex-row h-100">
                            <!-- Temperature -->
                            <div class="bg-white-50 rounded-4 p-3 me-3 w-50">
                                <div class="d-flex mb-3 align-items-center">
                                    <i class="fas fa-thermometer-half fa-lg me-2 mb-1"></i>
                                    <h5 class="h5 me-2">Temperature</h5>
                                    <label class="switch ms-auto">
                                        <input type="checkbox" class="apple-switch" checked>
                                    </label>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex flex-column me-auto">
                                        <span class="fs-1">25°C</span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <button class="btn btn-sm btn-sh mb-2" type="button">
                                            <i class="fas fa-chevron-up fa-lg"></i>
                                        </button>
                                        <button class="btn btn-sm btn-sh" type="button">
                                            <i class="fas fa-chevron-down fa-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-auto">
                                    <canvas id="temperatureChart" width="100%" height="80"></canvas>
                                </div>
                            </div>
                            <!-- Humidifier -->
                            <div class="bg-white-50 rounded-4 p-3 w-50">
                                <div class="d-flex mb-3 align-items-center">
                                    <i class="fa fa-tint fa-lg me-2 mb-1"></i>
                                    <h5 class="h5 me-2">Humidifier</h5>
                                    <label class="switch ms-auto">
                                        <input type="checkbox" class="apple-switch" checked>
                                    </label>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex flex-column me-auto">
                                        <span class="fs-1">50%</span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <button class="btn btn-sm btn-sh mb-2" type="button">
                                            <i class="fas fa-chevron-up fa-lg"></i>
                                        </button>
                                        <button class="btn btn-sm btn-sh" type="button">
                                            <i class="fas fa-chevron-down fa-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-auto">
                                    <canvas id="humidityChart" width="100%" height="80"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <!-- Camera -->
                        <div class="card bg-white-50 rounded-4 h-100">
                            <div class="d-flex position-relative security-cam overflow-hidden" data-cams="[
                                {
                                    &quot;name&quot;: &quot;Camera 1&quot;,
                                    &quot;src&quot;: &quot;img/camera1.jpg&quot;
                                },
                                {
                                    &quot;name&quot;: &quot;Camera 2&quot;,
                                    &quot;src&quot;: &quot;img/camera2.jpg&quot;
                                },
                                {
                                    &quot;name&quot;: &quot;Camera 3&quot;,
                                    &quot;src&quot;: &quot;img/camera3.jpg&quot;
                                }
                            ]">
                                <div class="position-absolute top-0 start-0 end-0 mt-2 ms-3 me-3 d-flex flex-row">
                                    <h2 class="h5 text-white-50 mb-0 cam-name">
                                        <button class="btn btn-sm me-2 badge bg-white-50 text-dark fw-normal"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Camera 1
                                            <i class="fas fa-chevron-down fa-sm"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Camera 1</a></li>
                                            <li><a class="dropdown-item" href="#">Camera 2</a></li>
                                            <li><a class="dropdown-item" href="#">Camera 3</a></li>
                                        </ul>
                                    </h2>

                                    <button class="btn btn btn-sm btn-sh ms-auto">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh ms-2 btn-delete">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh ms-2" data-bs-toggle="modal"
                                        data-bs-target="#addCameraModal">
                                        <i class="fas fa-plus fa-lg"></i>
                                    </button>
                                </div>
                                <div
                                    class="position-absolute bottom-0 start-0 end-0 mb-2 ms-3 me-3 d-flex flex-row flex-wrap justify-content-center">
                                    <button class="btn btn-sm btn-sh" data-cam="prev">
                                        <i class="fas fa-chevron-left fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh ms-2" data-cam="next">
                                        <i class="fas fa-chevron-right fa-lg"></i>
                                    </button>
                                </div>
                                <img src="img/camera.jpg" alt="" class="w-100 rounded-4">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="bg-white-50 rounded-4">
                            <div class="d-flex align-items-center p-2 px-3 pb-3">
                                <i class="fas fa-person-booth fa-lg me-2"></i>
                                <h2 class="h5">Shading Control</h2>
                            </div>
                            <div class="d-flex align-items-center px-3 pb-3">
                                <div class="d-flex flex-column flex-fill me-4">
                                    <span class="fs-1">50%</span>

                                    <input type="range" class="form-range ms-2 me-2" min="0" max="100" step="1"
                                        value="50">
                                </div>
                                <div class="d-flex flex-column ms-auto">
                                    <button class="btn btn-sm btn-sh mb-2" type="button">
                                        <i class="fas fa-chevron-up fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh" type="button">
                                        <i class="fas fa-chevron-down fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Wifi -->
                        <div class="bg-white-50 rounded-4 mt-3">
                            <div class="d-flex align-items-center p-2 px-3 pb-0">
                                <i class="fas fa-wifi fa-lg text-dark me-2"></i>
                                <h2 class="h5 me-2">Wi-Fi</h2>
                                <button class="btn btn-sm btn-sh ms-auto">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex align-items-center px-3">
                                <div class="d-flex flex-column flex-fill my-3">
                                    <span class="fs-4 text-muted">Connected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <!-- TV control -->
                        <div class="card bg-white-50 rounded-4">
                            <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                <i class="fas fa-tv fa-lg text-dark me-2"></i>
                                <h2 class="h5 me-auto">TV Control</h2>
                                <!-- On/Off -->
                                <button class="btn btn-sm btn-sh">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Channel -->
                                <div class="d-flex flex-row align-items-center mb-3">
                                    <h5 class="flex-grow-1 mb-0">Channel 1</h5>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-dark"><i
                                                class="fas fa-chevron-left"></i></button>
                                        <button class="btn btn-sm btn-outline-dark"><i
                                                class="fas fa-chevron-right"></i></button>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <i class="fas fa-volume-up fa-lg text-dark me-2"></i>
                                    <input type="range" class="form-range" min="0" max="100" step="1" value="0"
                                        style="width: 200px;">
                                </div>
                            </div>
                        </div>

                        <!-- Fire/CO Alarm -->
                        <div class="card bg-white-50 rounded-4 mt-3">
                            <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                <h2 class="h5">Fire/CO Alarm</h2>
                                <i class="fas fa-fire fa-lg text-dark"></i>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row align-items-center">
                                    <div class="d-flex flex-row align-items-center">
                                        <i class="fas fa-smog fa-2x text-dark me-2"></i>
                                        <span class="text-dark">No smoke detected</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="card bg-white-50 rounded-4 h-100 speaker">
                            <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                <h2 class="h5">Speaker</h2>
                                <i class="fas fa-music fa-lg text-dark"></i>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row align-items-center">
                                    <img src="https://i.scdn.co/image/ab67616d0000b273b6d4566db0d12894a1a3b7a2" alt=""
                                        class="rounded-2 shadow song-cover" width="75" height="75">
                                    <div class="ms-3">
                                        <span class="d-block song-title">Undisclosed Desires</span>
                                        <span class="text-muted d-block song-artist">Muse</span>
                                        <span class="text-muted song-album">The Resistance</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-3 gap-2">
                                    <button class="btn btn-sm rounded-4 my-auto d-flex align-items-center btn-random"><i
                                            class="fas fa-random fs-5"></i></button>
                                    <button class="btn btn-sm rounded-4 my-auto d-flex align-items-center btn-prev"><i
                                            class="fas fa-backward fs-5"></i></button>
                                    <button class="btn btn-sm fa-2x btn-play">
                                        <i class="fas fa-play-circle fa-2x text-sh"></i>
                                    </button>
                                    <button class="btn btn-sm rounded-4 my-auto d-flex align-items-center btn-next"><i
                                            class="fas fa-forward fs-5"></i></button>
                                    <button class="btn btn-sm rounded-4 my-auto d-flex align-items-center btn-toggle"><i
                                            class="fas fa-redo fs-5"></i></button>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <button class="btn btn-sm rounded-circle mr-2 btn-mute">
                                        <i class="fas fa-volume-up"></i>
                                    </button>
                                    <input type="range" class="form-range volume-range" min="0" max="100" value="50"
                                        style="max-width: 220px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php
    include 'components/editLampModal.php';
    include 'components/addDeviceModal.php';
    include 'components/editRoomModal.php';
    include 'components/addLampModal.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/app.js"></script>
</body>

</html>