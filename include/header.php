<?php
if (!isset($_SESSION['program'])) {
    echo '<script>
window.location.href="index.php"
</script>';
}
$Sessposition =  $_SESSION["postion"];
$limited = "";
$limited1 = "";

if ($Sessposition == "chairperson") {
    $limited = "hidden";
}
if ($Sessposition == "admin") {
    $limited1 = "hidden";
    $url = "admin_dashboard.php";
}
if ($Sessposition == "dean") {
    $limited1 = "hidden";
    $url = "dean_dashboard.php";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="CSS\select2.min.css">
    <!-- sidebar style -->
    <link rel="stylesheet" href="CSS/dashboard.css" />
    <!-- table style -->
    <link rel="stylesheet" href="CSS/content.css" />
    <title>Schedulemate</title>
    <style>
        table.dataTable tbody tr.myeven {
            background-color: lightblue;
        }

        table.dataTable tbody tr.myodd {
            background-color: #ff9248;
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="secondary-bg" id="sidebar-wrapper">
            <!-- changed -->
            <div class="sidebar-heading text-center primary-text fw-bold border-bottom">
                <img src="images/logo.png" alt="smlogo" class="logo">
            </div>

            <!-- sidebar menu -->
            <div class="list-group list-group-flush my-3">
                <a <?= $limited ?> href="<?= $url ?>" class="list-group-item list-group-item bg-transparent second-text fw-bold"><i class="fas fa-house me-2"></i>Dashboard</?a>

                    <!-- entries -->
                    <a <?= $limited1 ?> href="#" class="list-group-submenu list-group-item bg-transparent second-text fw-bold"><i class="fas fa-square-plus me-2"></i>Entries <i class="fa-solid fa-caret-down"></i></a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="section_index.php" class="submenu-item list-group-item bg-transparent second-text fw-bold">Sections</a>
                            </li>
                            <li>
                                <a href="prof_index.php" class="submenu-item list-group-item bg-transparent second-text fw-bold">Professors</a>
                            </li>
                            <li>
                                <a href="subject_index.php" class="submenu-item list-group-item bg-transparent second-text fw-bold">Subjects</a>
                            </li>
                            <li>
                                <a href="room_index.php" class="submenu-item list-group-item bg-transparent second-text fw-bold">Rooms</a>
                            </li>
                        </ul>
                    </div>

                    <!-- schedule -->
                    <a <?= $limited1 ?> href="schedule_index.php" class="list-group-item list-group-item bg-transparent second-text fw-bold"><i class="fas fa-regular fa-calendar-plus me-2"></i>Schedule</a>

                    <!-- USER LIST -->
                    <a <?= $limited ?> href="user_list.php" class="list-group-item list-group-item bg-transparent second-text fw-bold"><i class="fas fa-user me-2"></i>User List</a>


                    <!-- reports -->
                    <a href="#" class="list-group-submenu list-group-item bg-transparent second-text fw-bold"><i class="fas fa-solid fa-clipboard me-2"></i>Reports <i class="fa-solid fa-caret-down"></i></a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="pbs_index.php" class="submenu-item list-group-item bg-transparent second-text fw-bold">PBS</a>
                            </li>
                            <li>
                                <a href="pbt_index.php" class="submenu-item list-group-item bg-transparent second-text fw-bold">PBT</a>
                            </li>
                            <li>
                                <a href="pbru_index.php" class="submenu-item list-group-item bg-transparent second-text fw-bold">PBRU</a>
                            </li>
                            <li>
                                <a href="mis_index.php" class="submenu-item list-group-item bg-transparent second-text fw-bold">MIS</a>
                            </li>
                            <li>
                                <a href="subjects_pdf.php" class="submenu-item list-group-item bg-transparent second-text fw-bold">Prospectus</a>
                            </li>
                        </ul>
                    </div>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">

                <!-- menu toggle -->
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left fs-4 me-3" id="menu-toggle"></i>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- profile settings -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i>

                                <?= ucwords($_SESSION['userFname']) ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="profile_index.php">Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>