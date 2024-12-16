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
          <li class="nav-item">
            <a href="./book-appointment.php">
              <i class="la la-calendar-check-o"></i>
              <p>Book Appointment</p>
            </a>
          </li>
          <li class="nav-item  active">
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
          <h4 class="page-title">Appointment Status</h4>
          <!-- Inner Start -->
          <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-center rounded p-4">


              <?php
              // Fetching the user's appointment status
              $appointment_query = mysqli_query($connect, "SELECT * FROM `appointment` WHERE student_id = $user_id ORDER BY booking_time DESC LIMIT 1");

              if ($appointment_query) {
                $appointment_row = mysqli_fetch_assoc($appointment_query);

                $appointment_id = $appointment_row["appointment_id"] ?? null;
                $appointment_status = $appointment_row["appointment_status"] ?? null;
                $appointment_date = $appointment_row["appointment_date"] ?? null;
                $appointment_date = strtotime($appointment_date) ?? null;
                $appointment_date = date("d, D M Y", $appointment_date) ?? null;
                $appointment_time = $appointment_row["appointment_time"] ?? null;
                $appointment_time = strtotime($appointment_time) ?? null;
                $appointment_time = date("h:i:s a", $appointment_time) ?? null;
              }

              switch ($appointment_status) {
                case 'PENDING':
                  echo '
       <div class="container" style="margin-top: 50px;">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-center">Your Appointment is Pending!</h5>
          <p class="card-text">
            Your appointment has been booked and current status is pending. Check your appointment status regularly to know if it is approved or not.
          </p>
          <button class="btn btn-warning text-white" data-toggle="modal" data-target="#pendingModal">
            View Appointment Details
          </button>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Bootstrap Modal -->
<div class="modal fade" id="pendingModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Appointment ID: ' . $appointment_id . ' </p>
        <p>Date: ' . $appointment_date . '</p>
        <p>Time: ' . $appointment_time . '</p>
      </div>
      <div class="modal-footer">
        <a href="process-cancel-appointment.php?appointment_id=' . $appointment_id . '" class="btn btn-danger">Cancel Appointment</a>
      </div>
    </div>
  </div>
</div>
<!--End of  Bootstrap Modal -->


       ';
                  break;


                case 'APPROVED':
                  echo '
          <div class="container" style="margin-top: 50px;">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title text-center">Your Appointment is Approved!</h5>
                  <p class="card-text">
                    Your appointment has been approved. We look forward to seeing you on your scheduled date.
                  </p>
                  <button class="btn btn-primary" data-toggle="modal" data-target="#appointmentModal">
                    View Appointment Details
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        
        <!-- Bootstrap Modal -->
        <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <p>Appointment ID: ' . $appointment_id . ' </p>
              <p>Date: ' . $appointment_date . '</p>
              <p>Time: ' . $appointment_time . '</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!--End of  Bootstrap Modal -->
        
          ';
                  break;


                case 'DECLINED':
                  echo '
            <div class="container" style="margin-top: 50px;">
            <div class="row justify-content-center">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title text-center">Your Appointment is Not Approved</h5>
                    <p class="card-text">
                      We regret to inform you that your appointment request has not been approved. If you have any questions or concerns, please contact us.
                    </p>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#declinedModal">
                      View Details
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Bootstrap Modal -->
          <div class="modal fade" id="declinedModal" tabindex="-1" role="dialog" aria-labelledby="declinedModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="declinedModalLabel">Declined Appointment Details</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p>Appointment ID: ' . $appointment_id . ' </p>
                  <p>Date: ' . $appointment_date . '</p>
                  <p>Time: ' . $appointment_time . '</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
                      
            ';
                  break;


                default:
                  echo '
        <div class="container" style="margin-top: 50px;">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title text-center">You have not initiated any Appointment yet</h5>
                <p class="card-text">
                  Kindly book for an appointment if you would love to meet our doctors.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>            
        ';
                  break;
              }

              ?>











            </div>
          </div>
          <!-- Inner End -->
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