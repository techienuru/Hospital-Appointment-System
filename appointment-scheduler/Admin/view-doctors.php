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

    // Including Module for editing Doctor information
    include("./edit-doctor.php");
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
                    <li class="nav-item">
                        <a href="add-doctor.php">
                            <i class="la la-history"></i>
                            <p>Add Doctor</p>
                        </a>
                    </li>
                    <li class="nav-item active">
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
                    <h4 class="page-title">All Appointments History</h4>
                    <div class="row row-card-no-pd">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">All Appointments</div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">Doctor's Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Specialization</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            //Selecting Registered Doctors
                                            $reg_doc = mysqli_query($connect, "SELECT * FROM `doctor`");


                                            while ($reg_doc_fetch = mysqli_fetch_assoc($reg_doc)) {
                                                $doctor_id = $reg_doc_fetch["doctor_id"];
                                                $doctor_firstname = $reg_doc_fetch["firstname"];
                                                $doctor_lastname = $reg_doc_fetch["lastname"];
                                                $doctor_othername = $reg_doc_fetch["othername"] ?? null;
                                                $doctor_email = $reg_doc_fetch["email"];
                                                $doctor_password = $reg_doc_fetch["password"];
                                                $doctor_specialization = $reg_doc_fetch["specialization"] ?? 'Not set';

                                                echo '
                                <tr>
                                    <td>' . $doctor_firstname . " " . $doctor_lastname . " " . $doctor_othername . ' </td>
                                    <td>' . $doctor_email  . '</td>
                                    <td>' . $doctor_specialization  . '</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-target="#changePasswordModal' . $doctor_id . '" data-toggle="modal">Edit</button>
                                        <a href="delete-doctor.php?doctor_id=' . $doctor_id . '" class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                    
                                </tr>
                            ';


                                                echo '
                            <!-- Modal for changing Doctor Password  -->
                            <div class="modal fade" id="changePasswordModal' . $doctor_id . '" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <form action="view_doctor.php" method="POST">
                                <div class="form-floating">
                                    <input type="hidden" name="doctor_id" class="form-control" id="floatingText" value="' . $doctor_id . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="floatingText">First Name</label>
                                    <input type="text" name="firstname" class="form-control" id="floatingText" value="' . $doctor_firstname . '" required>
                                    <p class="text-danger"> ' . $firstname_err . '</p>
                                </div>
            
                                <div class="form-group">
                                    <label for="floatingText">Last Name</label>
                                    <input type="text" name="lastname" class="form-control" id="floatingText" value="' . $doctor_lastname . '" required>
                                    <p class="text-danger">' . $lastname_err . '</p>
                                </div>
                                
                                <div class="form-group">
                                    <label for="floatingText">Other Name</label>
                                    <input type="text" name="othername" class="form-control" id="floatingText" value="' . $doctor_othername . '">
                                    <p class="text-danger"> ' . $othername_err . '</p>
                                </div>
            
            
                                <div class="form-group">
                                    <label for="floatingPassword">Password</label>
                                    <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="New Password" required>
                                    <p class="text-danger">' . $password_err . '</p>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary py-3 w-100 mb-4">Save</button>
                            </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                            </div>
                    <!--End of  Modal for changing Doctor Password -->            
                            
                            ';
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