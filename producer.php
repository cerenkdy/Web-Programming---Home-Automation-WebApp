<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php?type=producers
if (!isset($_SESSION['user'])) {
    header("Location: login.php?type=producers");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Producer | <?php echo $name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/app.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 bg-light border-bottom shadow-sm">
        <div class="d-flex align-items-center">
            <h5 class="my-0 mr-md-auto fw-normal text-dark">Smart Home</h5>
            <a href="#" class="text-dark ml-1"><i
                class="fas fa-sign-out-alt fa-lg fa-flip-horizontal"></i></a>
        </div>
        <!-- Send Data Button -->
        <a class="btn btn-sh btn-sm ms-auto" href="">Send Data</a>
    </div>
    <main class="d-flex flex-nowrap lrform">
        <!-- Devices -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2 mw-100">
            <div class="devices w-100">
                <div class="d-flex align-items-center">
                    <h4 class="flex-grow-1 mr-2 mb-0 text-white fw-bold">Devices</h4>
                </div>
                <!-- Living Room -->
                <div class="d-flex flex-column mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5">Living room</h5>
                    </div>
                    <div class="d-flex flex-wrap flex-row gap-3 flex-space-between">
                        <div class="bg-white-50 shadow p-3 rounded-4 room-device d-flex flex-column" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class=""></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto on-off-btn" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex h-100">
                                <div class="d-flex flex-column">
                                    <span class="h5">device-1</span>
                                    <span class="text-muted mt-auto">On</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bedroom -->
                <div class="d-flex flex-column mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5">Bedroom</h5>
                    </div>
                    <div class="d-flex flex-wrap flex-row gap-3 flex-space-between">
                        <div class="bg-white-50 shadow p-3 rounded-4 room-device d-flex flex-column" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                </div>
                                <button class="btn btn-sm text-dark ms-auto on-off-btn" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex h-100">
                                <div class="d-flex flex-column">
                                    <span class="h5">device-1</span>
                                    <span class="text-muted mt-auto">On</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
                <!-- Kitchen -->
                <div class="d-flex flex-column mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5">Kitchen</h5>
                    </div>
                    <div class="d-flex flex-wrap flex-row gap-3 flex-space-between">
                        <div class="bg-white-50 shadow p-3 rounded-4 room-device d-flex flex-column" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                </div>
                                <button class="btn btn-sm text-dark ms-auto on-off-btn" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex h-100">
                                <div class="d-flex flex-column">
                                    <span class="h5">device-1</span>
                                    <span class="text-muted mt-auto">On</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bathroom -->
                <div class="d-flex flex-column mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5">Bathroom</h5>
                    </div>
                    <div class="d-flex flex-wrap flex-row gap-3 flex-space-between">
                        <div class="bg-white-50 shadow p-3 rounded-4 room-device d-flex flex-column" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                </div>
                                <button class="btn btn-sm text-dark ms-auto on-off-btn" type="button">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex h-100">
                                <div class="d-flex flex-column">
                                    <span class="h5">device-1</span>
                                    <span class="text-muted mt-auto">Off</span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/app.js"></script>
</body>

</html>