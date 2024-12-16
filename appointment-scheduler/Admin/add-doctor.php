<?php
session_start();
include("../include/connect.php");

$firstname_err = null;
$lastname_err = null;
$othername_err = null;
$email_err = null;
$password_err = null;


if (isset($_SESSION["admin_id"])) {

    //LOGGED IN USER ID
    $user_id = $_SESSION["admin_id"];

    $sql = mysqli_query($connect, "SELECT * FROM `admin` WHERE admin_id = $user_id");

    $fetch = mysqli_fetch_assoc($sql);


    $fullname = $fetch["fullname"];
    $email = $fetch["email"];

    if (isset($_POST["submit"])) {
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $othername = $_POST["othername"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        //VALIDATION 1 CHECKING FOR SPECIAL CHARACTERS
        $firstname = htmlspecialchars($firstname);
        $lastname = htmlspecialchars($lastname);
        $othername = htmlspecialchars($othername);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars($password);

        //FETCHING ALREADY REGISTERED EMAIL
        $fetch_email = mysqli_query($connect, "SELECT * FROM `doctor` WHERE email = '$email'");

        //VALIDATION 2 CHECKING FOR WRONG INPUTS 
        if (!preg_match('/^[a-zA-Z]+$/u', $firstname) || !preg_match('/^[a-zA-Z]+$/u', $lastname) || !filter_var($email, FILTER_VALIDATE_EMAIL) || mysqli_num_rows($fetch_email) > 0 || !preg_match('/^[a-zA-Z0-9]+$/u', $password) || strlen($password) < 6) {

            if (!preg_match('/^[a-zA-Z]+$/u', $firstname)) {
                $firstname_err = "Invalid characters in first name.";
            }

            if (!preg_match('/^[a-zA-Z]+$/u', $lastname)) {
                $lastname_err = "Invalid characters in last name.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_err = "Invalid email address.";
            }
            if (mysqli_num_rows($fetch_email) > 0) {
                $email_err = "Email already registered.";
            }

            if (!preg_match('/^[a-zA-Z0-9]+$/u', $password)) {
                $password_err = "Invalid Password.";
            }

            if (strlen($password) < 6) {
                $password_err = "Password must be up to six characters";
            }
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = mysqli_query($connect, "INSERT INTO `doctor`(firstname,lastname,othername,email,password) VALUES('$firstname','$lastname','$othername','$email','$password_hash')");

            if ($sql = true) {
                echo "
            <script>
                alert('Doctor added successful!');
                window.location.href='view-doctors.php';
            </script>
            ";
            } else {
                echo "<script>
                alert('Signup error:/n" . mysqli_error($connect) . "');
            </script>";
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
                                <?php echo $fullname; ?>
                                <span class="user-level">Admin</span>
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
                        <a href="./new-appointments.php">
                            <i class="la la-calendar-check-o"></i>
                            <p>New Appointment</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="./approved-appointments.php">
                            <i class="la la-check-square"></i>
                            <p>Approved Appointment</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="./cancelled-appointments.php">
                            <i class="la la-times-circle"></i>
                            <p>Cancelled Appointment</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="all-appointments.php">
                            <i class="la la-history"></i>
                            <p>All Appointments history</p>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="add-doctor.php">
                            <i class="la la-history"></i>
                            <p>Add Doctor</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="view-doctors.php">
                            <i class="la la-history"></i>
                            <p>View Doctors</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="patients.php">
                            <i class="la la-history"></i>
                            <p>Patients</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <h4 class="page-title">Add Doctor</h4>
                    <div class="row row-card-no-pd">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Fill the form below to add a new Doctor</div>
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="firstname">First Name</label>
                                                    <input type="text" class="form-control input-pill" name="firstname" placeholder="" required>
                                                    <p class="text-danger"><?php echo $firstname_err; ?></p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="lastname">Last Name</label>
                                                    <input type="text" class="form-control input-pill" name="lastname" placeholder="" required>
                                                    <p class="text-danger"><?php echo $lastname_err; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="othername">Other Name</label>
                                                    <input type="text" class="form-control input-pill" name="othername" placeholder="" required>
                                                    <p class="text-danger"><?php echo $othername_err; ?></p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="email">Email Address</label>
                                                    <input type="email" class="form-control input-pill" name="email" placeholder="His/her email..." required>
                                                    <p class="text-danger"><?php echo $email_err; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control input-pill" name="password" placeholder="" required>
                                                    <p class="text-danger"><?php echo $password_err; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" name="submit" class="btn btn-success">Add</button>
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