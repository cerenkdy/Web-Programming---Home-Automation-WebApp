<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Devices - Smart Home</title>
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
        <!-- Devices -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2 mw-100">
            <div class="devices w-100">
                <div class="d-flex align-items-center">
                    <h4 class="flex-grow-1 mr-2 mb-0 text-white">Devices</h4>
                </div>
                <!-- Living room -->
                <div class="d-flex flex-column mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5">Living Room</h5>
                        <button class="btn btn-sm text-white" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                            <i class="fas fa-plus fa-lg"></i>
                        </button>
                        <div class="dropdown ms-2">
                            <button class="btn btn-sm text-white" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-lg"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#addDeviceModal">Add Device</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Device List -->
                    <div class="d-flex flex-wrap flex-row gap-3 flex-space-between">
                        <!-- Lamp -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-lightbulb fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Main Lamp</span>
                                    <span class="text-muted">On</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Air Conditioner-->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-wind fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Air Conditioner</span>
                                    <span class="text-muted">Off</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Camera -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-start align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-video fa-lg"></i>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Camera 1</span>
                                    <span class="text-muted">Recording</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- TV -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-tv fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">TV</span>
                                    <span class="text-muted">Channel: 1</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Speaker -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-volume-up fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-stop fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Speaker</span>
                                    <span class="text-muted">Playing</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Vacuum Cleaner -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-broom fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-play fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Vacuum Cleaner</span>
                                    <span class="text-muted">Charging</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bedroom -->
                <div class="d-flex flex-column mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5">Bedroom</h5>
                        <button class="btn btn-sm text-white" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                            <i class="fas fa-plus fa-lg"></i>
                        </button>
                        <div class="dropdown ms-2">
                            <button class="btn btn-sm text-white" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-lg"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#addDeviceModal">Add Device</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Device List -->
                    <div class="d-flex flex-wrap flex-row gap-3">
                        <!-- Lamp -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-lightbulb fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Bedroom Lamp</span>
                                    <span class="text-muted">On</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Air Conditioner -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-wind fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Air Conditioner</span>
                                    <span class="text-muted">Off</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- TV -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-tv fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">TV</span>
                                    <span class="text-muted">Channel: 1</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Speaker -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-volume-up fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-stop fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Speaker</span>
                                    <span class="text-muted">Playing</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Kitchen -->
                <div class="d-flex flex-column mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5">Kitchen</h5>
                        <button class="btn btn-sm text-white" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                            <i class="fas fa-plus fa-lg"></i>
                        </button>
                        <div class="dropdown ms-2">
                            <button class="btn btn-sm text-white" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-lg"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#addDeviceModal">Add Device</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Device List -->
                    <div class="d-flex flex-wrap flex-row gap-3">
                        <!-- Lamp -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-lightbulb fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Kitchen Lamp</span>
                                    <span class="text-muted">Off</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Air Conditioner -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-wind fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Air Conditioner</span>
                                    <span class="text-muted">Off</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Vacuum Cleaner -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-broom fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-play fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Vacuum Cleaner 2</span>
                                    <span class="text-muted">Charging</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bathroom -->
                <div class="d-flex flex-column mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5">Bathroom</h5>
                        <button class="btn btn-sm text-white" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                            <i class="fas fa-plus fa-lg"></i>
                        </button>
                        <div class="dropdown ms-2">
                            <button class="btn btn-sm text-white" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-lg"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#addDeviceModal">Add Device</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Device List -->
                    <div class="d-flex flex-wrap flex-row gap-3">
                        <!-- Lamp -->
                        <div class="bg-white-50 shadow p-3 rounded-4" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-lightbulb fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex flex-column">
                                    <span class="h5">Bathroom Lamp</span>
                                    <span class="text-muted">Off</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Device Modal -->
    <div class="modal fade" id="addDeviceModal">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Device</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Device Type -->
                    <div class="mb-3">
                        <label for="deviceType" class="form-label">Device Type</label>
                        <select class="form-select" id="deviceType">
                            <option selected disabled>Select device type</option>
                            <option value="1">Lamp</option>
                            <option value="2">Vacuum Cleaner</option>
                            <option value="3">Air Conditioner</option>
                            <option value="4">Television</option>
                            <option value="5">Refrigerator</option>
                            <option value="6">Washing Machine</option>
                        </select>
                    </div>

                    <!-- Device Name -->
                    <div class="mb-3">
                        <label for="deviceName" class="form-label">Device Name</label>
                        <input type="text" class="form-control" id="deviceName" placeholder="Enter device name">
                    </div>

                    <!-- Device Location -->
                    <div class="mb-3">
                        <label for="deviceLocation" class="form-label">Device Location</label>
                        <select class="form-select" id="deviceLocation">
                            <option selected disabled>Select device location</option>
                            <option value="1">Living Room</option>
                            <option value="2">Bedroom</option>
                            <option value="3">Kitchen</option>
                            <option value="4">Bathroom</option>
                        </select>
                    </div>

                    <!-- Device Status -->
                    <div class="mb-3">
                        <label for="deviceStatus" class="form-label">Device Status</label>
                        <select class="form-select" id="deviceStatus">
                            <option selected disabled>Select device status</option>
                            <option value="1">On</option>
                            <option value="2">Off</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sh">Add Device</button>
                </div>
            </form>
        </div>
    </div>

    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/app.js"></script>
</body>

</html>