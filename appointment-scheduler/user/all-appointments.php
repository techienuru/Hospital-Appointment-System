<?php
session_start();
include("../include/connect.php");

if (isset($_SESSION["student_id"])) {

    //LOGGED IN USER ID
    $user_id = $_SESSION["student_id"];

    $sql = mysqli_query($connect, "SELECT * FROM `student` WHERE student_id = $user_id");

    $fetch = mysqli_fetch_assoc($sql);


    $firstname = $fetch["firstname"];
    $lastname = $fetch["lastname"];
    $othername = $fetch["othername"];
    $email = $fetch["email"];
    $matric = $fetch["matric"];
} else {
    echo "
    <script>
        alert('Oops! You are not logged in!');
        window.location.href='../signin.php';
    </script>
    ";
}



?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Appointment Scheduler - Doctor's Dashboard</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="../assets/css/ready.css">
    <link rel="stylesheet" href="../assets/css/demo.css">
</head>

<body>
    <div class="wrapper">
        <div class="main-header">
            <div class="logo-header">
                <a href="index.html" class="logo">
                    Appointment Scheduler
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
            </div>
            <nav class="navbar navbar-header navbar-expand-lg">
                <div class="container-fluid">

                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item dropdown hidden-caret">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="la la-bell"></i>
                                <span class="notification">3</span>
                            </a>
                            <ul class="dropdown-menu notif-box" aria-labelledby="navbarDropdown">
                                <li>
                                    <div class="dropdown-title">You have 4 new notification</div>
                                </li>
                                <li>
                                    <div class="notif-center">
                                        <a href="#">
                                            <div class="notif-icon notif-success"> <i class="la la-comment"></i> </div>
                                            <div class="notif-content">
                                                <span class="block">
                                                    Rahmad commented on Admin
                                                </span>
                                                <span class="time">12 minutes ago</span>
                                            </div>
                                        </a>
                                    </div>
                                </li>
                                <li>
                                    <a class="see-all" href="javascript:void(0);"> <strong>See all notifications</strong> <i class="la la-angle-right"></i> </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="sidebar">
            <div class="scrollbar-inner sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img src="../assets/img/profile.png">
                    </div>
                    <div class="info">
                        <a class="" data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                            <span>
                                <?php echo "$firstname $lastname";?>
                                <span class="user-level">User</span>
                                <span class="caret"></span>
                            </span>
                        </a>
                        <div class="clearfix"></div>

                        <div class="collapse in" id="collapseExample" aria-expanded="true">
                            <ul class="nav">
                                <li>
                                    <a href="./profile.php">
                                        <span class="link-collapse">My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="./logout.php">
                                        <span class="link-collapse">Logout</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="nav">
                    <li class="nav-item">
                        <a href="./dashboard.php">
                            <i class="la la-dashboard"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="./book-appointment.php">
                            <i class="la la-calendar-check-o"></i>
                            <p>Book Appointment</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="./appointment-status.php">
                            <i class="la la-check-square"></i>
                            <p>Appointment Status</p>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="all-appointments.php">
                            <i class="la la-history"></i>
                            <p>All Appointments history</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <h4 class="page-title">All Appointments</h4>
                    <div class="container">
                        <?php

                        //Selecting Total appointment from DB
                        $total_appt = mysqli_query($connect, "SELECT COUNT(appointment_id) FROM `appointment` WHERE student_id = $user_id");
                        $total_appt_row = mysqli_fetch_row($total_appt);
                        $total_appt_no = $total_appt_row["0"];


                        //Selecting Pending appointment from DB
                        $pending_appt = mysqli_query($connect, "SELECT COUNT(appointment_id) FROM `appointment` WHERE student_id = $user_id AND appointment_status = 'PENDING'");
                        $pending_appt_row = mysqli_fetch_row($pending_appt);
                        $pending_appt_no = $pending_appt_row["0"];


                        //Selecting Declined appointment from DB
                        $declined_appt = mysqli_query($connect, "SELECT COUNT(appointment_id) FROM `appointment` WHERE student_id = $user_id AND appointment_status = 'declined'");
                        $declined_appt_row = mysqli_fetch_row($declined_appt);
                        $declined_appt_no = $declined_appt_row["0"];


                        //Selecting Approved appointment from DB
                        $approved_appt = mysqli_query($connect, "SELECT COUNT(appointment_id) FROM `appointment` WHERE student_id = $user_id AND appointment_status = 'approved'");
                        $approved_appt_row = mysqli_fetch_row($approved_appt);
                        $approved_appt_no = $approved_appt_row["0"];


                        ?>
                        <div class="row mb-5">
                            <div class="col-md-4">
                                <div class="card card-stats card-warning">
                                    <div class="card-body ">
                                        <div class="d-flex justify-content-between">
                                            <p class="card-category">Pending Appointments</p>
                                            <p>
                                                <!-- Display pending appointment details here -->
                                                <?php echo $pending_appt_no; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-danger">
                                    <div class="card-body ">
                                        <div class="d-flex justify-content-between">
                                            <p class="card-category">Canceled Appointments</p>
                                            <p>
                                                <!-- Display canceled appointment details here -->
                                                <?php echo $declined_appt_no; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-success">
                                    <div class="card-body ">
                                        <div class="d-flex justify-content-between">
                                            <p class="card-category">Approved Appointments</p>
                                            <p>
                                                <!-- Display approved appointment details here -->
                                                <?php echo $approved_appt_no; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Patient Name</th>
                                                        <th scope="col">Appointment ID</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Time</th>
                                                        <th scope="col">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    //Selecting All appointments from DB
                                                    $all_appt = mysqli_query($connect, "SELECT * FROM `appointment` INNER JOIN student ON appointment.student_id = student.student_id WHERE appointment.student_id = $user_id");

                                                    while ($all_appt_row = mysqli_fetch_row($all_appt)) {
                                                        $all_appt_fullname = $all_appt_row["9"] . " " . $all_appt_row["10"];
                                                        $all_appt_appointment_id = $all_appt_row["0"];
                                                        $all_appt_appointment_date = $all_appt_row["4"];
                                                        //Modifying appointment date
                                                        $all_appt_appointment_date = strtotime($all_appt_appointment_date);
                                                        $all_appt_appointment_date = date("D, d-M-Y", $all_appt_appointment_date);

                                                        $all_appt_appointment_time = $all_appt_row["5"];
                                                        //Modifying appointment time
                                                        $all_appt_appointment_time = strtotime($all_appt_appointment_time);
                                                        $all_appt_appointment_time = date("h:i:s a", $all_appt_appointment_time);

                                                        $all_appt_appointment_status = $all_appt_row["6"];
                                                        echo '<tr>';
                                                        echo '
    <td>' . $all_appt_fullname . '</td>
    <td>' . $all_appt_appointment_id . '</td>
    <td>' . $all_appt_appointment_date . '</td>
    <td>' . $all_appt_appointment_time . '</td>
  ';
                                                        switch ($all_appt_appointment_status) {
                                                            case 'PENDING':
                                                                echo '<td><span class="text-warning">' . $all_appt_appointment_status . '</span></td>';
                                                                break;

                                                            case 'APPROVED':
                                                                echo '<td><span class="text-success">' . $all_appt_appointment_status . '</span></td>';
                                                                break;

                                                            case 'DECLINED':
                                                                echo '<td><span class="text-danger">' . $all_appt_appointment_status . '</span></td>';
                                                                break;

                                                            default:
                                                                # code...
                                                                break;
                                                        }

                                                        echo '</tr>';
                                                    }

                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<script src="../assets/js/core/jquery.3.2.1.min.js"></script>
<script src="../assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugin/chartist/chartist.min.js"></script>
<script src="../assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js"></script>
<script src="../assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="../assets/js/plugin/jquery-mapael/jquery.mapael.min.js"></script>
<script src="../assets/js/plugin/jquery-mapael/maps/world_countries.min.js"></script>
<script src="../assets/js/plugin/chart-circle/circles.min.js"></script>
<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="../assets/js/ready.min.js"></script>
<script src="../assets/js/demo.js"></script>

</html>