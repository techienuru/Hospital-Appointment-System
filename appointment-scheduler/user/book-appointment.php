<?php
session_start();
include("../include/connect.php");

// checking for current date
$today = date("Y-m-d");

// checking and automatically declining appointment when date is passed
$deletingAppointment = mysqli_query($connect, "SELECT * FROM `appointment` WHERE appointment_status = 'PENDING' AND DATE(appointment_date) < '$today'");

if (mysqli_num_rows($deletingAppointment) > 0) {
    while ($row = mysqli_fetch_assoc($deletingAppointment)) {
        $appt_id = $row["appointment_id"];
        $changeApptStatus = mysqli_query($connect, "UPDATE `appointment` SET appointment_status = 'DECLINED' WHERE appointment_id = '$appt_id'");
    }
}



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

    $user_fullname = "$firstname $lastname $othername";
    $user_email = "$email";

    if (isset($_POST["submit"])) {
        // Nigeria Timezone
        date_default_timezone_set("AFRICA/LAGOS");

        $phone_number = $_POST["phone_number"];
        $appointment_date = $_POST["appointment_date"];
        $appointment_time = $_POST["appointment_time"];

        $appointment_time = strtotime($appointment_time);
        $appointment_time = date("H:i:s", $appointment_time);


        $prefix = "AS";
        $uniqueId = substr(uniqid(), 6, 4);
        $suffix = "CHEKUP";

        $appointment_id = $prefix . $user_id . $uniqueId . $suffix;

        $selecting = mysqli_query($connect, "SELECT * FROM `appointment` WHERE student_id = $user_id AND appointment_status = 'PENDING'");

        // checking maximum number of appointment
        $selectingMaxAppt = mysqli_query($connect, "SELECT * FROM `appointment` WHERE DATE(appointment_date) = '$appointment_date'");

        // checking if appointment date less than current date
        $todaysDate = strtotime("Now");
        $todaysDate = date("Y-m-d", $todaysDate);

        $todaysDate = date("Y-m-d");  // Get today's date
        $todaysTime = strtotime("Now");
        $todaysTime = date("H:i a", $todaysTime);  // Get the current time in 24-hour format

        $appointment_date = $_POST['appointment_date'];  // Assume this is provided via POST
        $appointment_time = $_POST['appointment_time'];  // Assume this is provided via POST


        if (mysqli_num_rows($selectingMaxAppt) > 20 || $appointment_date < $todaysDate || ($appointment_date == $todaysDate && $appointment_time <= $todaysTime)) {
            if (mysqli_num_rows($selectingMaxAppt) > 20) {
                echo "
        <script>
            alert('Maximum number of appointment for this date reached! Please book for another date');
        </script>
    ";
            }
            if ($appointment_date < $todaysDate) {
                echo "
        <script>
            alert('Please book for an appointment greater than today!');
        </script>
    ";
            }

            if ($appointment_date == $todaysDate && $appointment_time <= $todaysTime) {
                echo "
        <script>
            alert('Oops! You can only book for time > current time');
        </script>
    ";
            }
        } else {
            // checking for an already existing appointment
            if (mysqli_num_rows($selecting) > 0) {
                echo "
        <script>
            alert('Your previous appointment status is still pending! You cannot book for another appointment.');
        </script>
        ";
            } else {

                $sql1 = mysqli_query($connect, "INSERT INTO `appointment` (appointment_id,fullname,email,phone_number,appointment_date,appointment_time,appointment_status,student_id) VALUES('$appointment_id','$user_fullname','$user_email','$phone_number','$appointment_date','$appointment_time','PENDING',$user_id)");

                if ($sql1) {
                    echo "
        <script>
            alert('Appointment booked successfully!');
            window.location.href='appointment-status.php';
        </script>
        ";
                } else {
                    $error_message = mysqli_error($connect);
                    echo "
            <script>
                alert('Error in Booking Appointment: $error_message');
            </script>
        ";
                }
            }
        }
    }
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
                                <?php echo "$firstname $lastname"; ?>
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
                    <li class="nav-item active">
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
                    <li class="nav-item">
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
                    <h4 class="page-title">Book Appointment</h4>
                    <div class="row row-card-no-pd">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Book an apponiment with the Doctor</div>
                                </div>
                                <div class="card-body">
                                    <form action="book-appointment.php" method="POST">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="fullname">Full Name</label>
                                                    <input type="text" class="form-control input-pill" name="fullname" id="fullname" value="<?php echo "$user_fullname"; ?>" required readonly>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="text" class="form-control input-pill" name="email" id="email" value="<?php echo "$user_email"; ?>" required readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="form-group">
                                                    <label for="phone-number">Phone Number</label>
                                                    <input type="text" class="form-control input-pill" name="phone_number" id="phone-number" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">Appointment Date</label>
                                                    <input type="date" class="form-control input-pill" name="appointment_date" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Appointment Time</label>
                                                    <input type="time" class="form-control input-pill" name="appointment_time" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" name="submit" class="btn btn-success">Submit Appointment Request</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePro" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h6 class="modal-title"><i class="la la-frown-o"></i> Under Development</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>Currently the pro version of the <b>Ready Dashboard</b> Bootstrap is in progress development</p>
                    <p>
                        <b>We'll let you know when it's done</b>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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