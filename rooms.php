<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rooms | <?php echo $name; ?></title>
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
        <!-- Rooms -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2 mw-100">
            <div class="rooms w-100">
                <div class="d-flex align-items-center mb-3">
                    <h4 class="flex-grow-1 mr-2 mb-0 text-white">Rooms</h4>
                    <button class="btn btn-secondary btn-sm ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addRoomModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-6 mb-3">
                        <a href=""
                            class="d-flex flex-column p-3 rounded text-dark text-decoration-none bg-white-50 mw-100">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex">
                                        <h5 class="mb-0">Living Room</h5>
                                        <div class="d-flex flex-row ms-auto">
                                            <div class="ms-3">
                                                <i class="fas fa-thermometer-half me-1"></i>
                                                <span>25°C</span>
                                            </div>
                                            <div class="ms-3">
                                                <i class="fas fa-tint me-1"></i>
                                                <span>50%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">11 devices</p>
                                </div>
                            </div>
                            <div class="d-flex flex-nowrap mt-3 gap-2 horizontal-scrollable">
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-lightbulb fa-1x"></i>
                                    <span>Lights</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-wind fa-1x"></i>
                                    <span>Air C.</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-wind fa-1x"></i>
                                    <span>Shading</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-wifi fa-1x"></i>
                                    <span>Wifi</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-camera fa-1x"></i>
                                    <span>Camera</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-fire fa-1x"></i>
                                    <span>Fire Det.</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-tv fa-1x"></i>
                                    <span>TV</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-music fa-1x"></i>
                                    <span>Speaker</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-robot fa-1x"></i>
                                    <span>Cleaner</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Bedroom-->
                    <div class="col-md-12 col-lg-6 mb-3">
                        <a href=""
                            class="d-flex flex-column p-3 rounded text-dark text-decoration-none bg-white-50 mw-100">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex">
                                        <h5 class="mb-0">Bedroom</h5>
                                        <div class="d-flex flex-row ms-auto">
                                            <div class="ms-3">
                                                <i class="fas fa-thermometer-half me-1"></i>
                                                <span>23°C</span>
                                            </div>
                                            <div class="ms-3">
                                                <i class="fas fa-tint me-1"></i>
                                                <span>48%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">4 devices</p>
                                </div>
                            </div>
                            <div class="d-flex flex-nowrap mt-3 gap-2 horizontal-scrollable">
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-lightbulb fa-1x"></i>
                                    <span>Lights</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-wind fa-1x"></i>
                                    <span>Air C.</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-tv fa-1x"></i>
                                    <span>TV</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-music fa-1x"></i>
                                    <span>Speaker</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Kitchen-->
                    <div class="col-md-12 col-lg-6 mb-3">
                        <a href=""
                            class="d-flex flex-column p-3 rounded text-dark text-decoration-none bg-white-50 mw-100">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex">
                                        <h5 class="mb-0">Kitchen</h5>
                                        <div class="d-flex flex-row ms-auto">
                                            <div class="ms-3">
                                                <i class="fas fa-thermometer-half me-1"></i>
                                                <span>25°C</span>
                                            </div>
                                            <div class="ms-3">
                                                <i class="fas fa-tint me-1"></i>
                                                <span>50%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">5 devices</p>
                                </div>
                            </div>
                            <div class="d-flex flex-nowrap mt-3 gap-2 horizontal-scrollable">
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-lightbulb fa-1x"></i>
                                    <span>Lights</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-wind fa-1x"></i>
                                    <span>Air C.</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-tint fa-1x"></i>
                                    <span>Shading</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-fire fa-1x"></i>
                                    <span>Fire Det.</span>
                                </div>
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-robot fa-1x"></i>
                                    <span>Cleaner</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Bathroom-->
                    <div class="col-md-12 col-lg-6 mb-3">
                        <a href=""
                            class="d-flex flex-column p-3 rounded text-dark text-decoration-none bg-white-50 mw-100">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex">
                                        <h5 class="mb-0">Bathroom</h5>
                                        <div class="d-flex flex-row ms-auto">
                                            <div class="ms-3">
                                                <i class="fas fa-thermometer-half me-1"></i>
                                                <span>18°C</span>
                                            </div>
                                            <div class="ms-3">
                                                <i class="fas fa-tint me-1"></i>
                                                <span>65%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">1 devices</p>
                                </div>
                            </div>
                            <div class="d-flex flex-nowrap mt-3 gap-2 horizontal-scrollable">
                                <div class="d-flex flex-column align-items-center card p-2 py-3 mw-50px">
                                    <i class="fas fa-lightbulb fa-1x"></i>
                                    <span>Lights</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include './components/addRoomModal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/app.js"></script>
</body>

</html>