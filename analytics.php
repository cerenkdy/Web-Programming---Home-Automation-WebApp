<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analytics - Smart Home</title>
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
        <!-- Dashboard -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2">
            <div class="dashboard w-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-light">Analytics</h4>
                    <!-- Period -->
                    <div class="btn-group">
                        <button class="btn btn-sm btn-sh dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fas fa-calendar-alt fa-lg me-2"></i>
                            <span>Period</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">Yesterday</a></li>
                            <li><a class="dropdown-item" href="#">This Week</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row d-flex flex-wrap">
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <!-- Electricity Usage -->
                        <div class="card bg-white-50 rounded-4 h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start p-3">
                                <h2 class="h6 pb-0">Electricity Usage</h2>
                                <span class="fs-4 m-auto">
                                    <i class="fas fa-bolt fa-lg text-warning"></i>
                                    5 KWh
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <!-- Water Usage -->
                        <div class="card bg-white-50 rounded-4 h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start p-3">
                                <h2 class="h6 pb-0">Water Usage</h2>
                                <span class="fs-4 m-auto">
                                    <i class="fas fa-lg text-primary fa-tint"></i>
                                    10 L
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <!-- Gas Usage -->
                        <div class="card bg-white-50 rounded-4 h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start p-3">
                                <h2 class="h6 pb-0">Gas Usage</h2>
                                <span class="fs-4 m-auto">
                                    <i class="fas fa-lg text-danger fa-fire"></i>
                                    5 L
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <!-- Temperature -->
                        <div class="card bg-white-50 rounded-4 h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start p-3">
                                <h2 class="h6 pb-0">Temperature</h2>
                                <span class="fs-4 m-auto">
                                    <i class="fas fa-lg text-info fa-thermometer-half"></i>
                                    25 °C
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex flex-wrap">
                    <div class="col-lg-12 mb-3">
                        <div class="card bg-white-50 rounded-4 h-100 consumption">
                            <div class="d-flex justify-content-between align-items-center  p-2 px-3">
                                <h2 class="h5">Consumption</h2>
                                <ul class="nav nav-pills card-header-pills">
                                    <li class="nav-item">
                                        <a href="#electric" data-consumption="Electric"
                                            class="nav-link text-dark">Electric</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#water" data-consumption="Water" class="nav-link text-dark">Water</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#gas" data-consumption="Gas" class="nav-link text-dark">Gas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#all" data-consumption="all" class="nav-link active btn-sh">All</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <canvas id="consumptionChart" width="100%" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/app.js"></script>
</body>

</html>