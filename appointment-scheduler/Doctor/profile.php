<?php
session_start();
include("../include/connect.php");

$new_password_err = null;

if (isset($_SESSION["doctor_id"])) {

  //LOGGED IN USER ID
  $user_id = $_SESSION["doctor_id"];

  $sql = mysqli_query($connect, "SELECT * FROM `doctor` WHERE doctor_id = $user_id");

  $fetch = mysqli_fetch_assoc($sql);


  $firstname = $fetch["firstname"];
  $lastname = $fetch["lastname"];
  $othername = $fetch["othername"];
  $email = $fetch["email"];
  if (isset($fetch["blood_group"])) {
    $bloodgroup = $fetch["blood_group"];
    $genotype = $fetch["genotype"];
    $specialization = $fetch["specialization"];
  } else {
    $bloodgroup = 'Not Set';
    $genotype = 'Not Set';
    $specialization = 'Not Set';
  }



  if (isset($_POST["submit"])) {
    $new_firstname = $_POST["firstname"];
    $new_lastname = $_POST["lastname"];
    $new_othername = $_POST["othername"];
    $new_email = $_POST["email"];
    $new_password = $_POST["password"];
    $new_bloodgroup = $_POST["bloodgroup"];
    $new_genotype = $_POST["genotype"];
    $new_specialization = $_POST["specialization"];

    if (strlen($new_password) < 6) {
      echo '
            <script>
              alert("Update not Successful!");
            </script>
        ';
      $new_password_err = "Password value must be up to six(6)";
    } else {
      //hashing updated password
      $new_password = password_hash($new_password, PASSWORD_DEFAULT);



      $updating_profile = mysqli_query($connect, "UPDATE `doctor` SET `firstname`='$new_firstname',`lastname`='$new_lastname',`othername`='$new_othername',`email`='$new_email',`password`='$new_password',`blood_group`='$new_bloodgroup',`genotype`='$new_genotype',`specialization`='$new_specialization' WHERE doctor_id = $user_id");

      if ($updating_profile) {
        echo '
            <script>
              alert("Update Successful!");
              window.location.href="profile.php";
            </script>
         ';
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
                <?php echo "$firstname $lastname $othername"; ?>
                <span class="user-level">Doctor</span>
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
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <div class="content">
        <div class="container-fluid">
          <h4 class="page-title">Update Profile</h4>
          <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-center rounded p-4">



              <div class="container w-75 m-auto">
                <div class="card personal-card">
                  <div class="card-body w-100">
                    <h4 class="card-title mb-4">Personal Information</h4>

                    <div class="d-flex justify-content-between">
                      <h6 class="mb-0">First Name</h6>
                      <p class="mb-0 text-truncate"><?php echo $firstname; ?></p>
                    </div>

                    <div class="d-flex justify-content-between">
                      <h6 class="mb-0">Last Name</h6>
                      <p class="mb-0 text-truncate"><?php echo $lastname; ?></p>
                    </div>

                    <div class="d-flex justify-content-between">
                      <h6 class="mb-0">Other Name</h6>
                      <p class="mb-0 text-truncate"><?php echo $othername; ?></p>
                    </div>

                    <div class="d-flex justify-content-between">
                      <h6 class="mb-0">Email</h6>
                      <p class="mb-0 truncated"><?php echo $email; ?></p>
                    </div>


                    <div class="d-flex justify-content-between">
                      <h6 class="mb-0">Blood Group</h6>
                      <p class="mb-0 truncated"><?php echo $bloodgroup; ?></p>
                    </div>


                    <div class="d-flex justify-content-between">
                      <h6 class="mb-0">Genotype</h6>
                      <p class="mb-0 truncated"><?php echo $genotype; ?></p>
                    </div>


                    <div class="d-flex justify-content-between">
                      <h6 class="mb-0">Specialization</h6>
                      <p class="mb-0 truncated"><?php echo $specialization; ?></p>
                    </div>
                  </div>
                </div>
              </div>


              <div class="container">
                <div class="row justify-content-center">
                  <div class="col-md-8">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title text-center">Profile Update</h5>
                        <form action="profile.php" method="post">
                          <div class="form-group">
                            <input type="text" class="form-control" value="<?php echo $firstname ?>" placeholder="First Name" name="firstname" required>
                          </div>

                          <div class="form-group">
                            <input type="text" class="form-control" value="<?php echo $lastname ?>" placeholder="Last Name" name="lastname" required>
                          </div>

                          <div class="form-group">
                            <input type="text" class="form-control" value="<?php echo $othername ?>" placeholder="Other Name" name="othername">
                          </div>


                          <div class="form-group">
                            <input type="email" class="form-control" value="<?php echo $email ?>" placeholder="Email" id="email" name="email" required>
                          </div>


                          <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password" id="email" name="password" required>
                          </div>

                          <div class="form-group">
                            <select class="form-control  bg-danger text-white" id="bloodGroup" name="bloodgroup" required>
                              <option value="">----Select Blood Group----</option>
                              <option value="A+">A+</option>
                              <option value="A-">A-</option>
                              <option value="B+">B+</option>
                              <option value="B-">B-</option>
                              <option value="AB+">AB+</option>
                              <option value="AB-">AB-</option>
                              <option value="O+">O+</option>
                              <option value="O-">O-</option>
                            </select>
                          </div>

                          <div class="form-group">
                            <select class="form-control bg-success text-white" id="genotype" name="genotype" required>
                              <option value="">----Select Genotype----</option>
                              <option value="AA">AA</option>
                              <option value="AS">AS</option>
                              <option value="SS">SS</option>
                              <option value="AC">AC</option>
                              <!-- Add more options as needed -->
                            </select>
                          </div>

                          <div class="form-group">
                            <select class="form-control  bg-danger text-white" id="bloodGroup" name="specialization" required>
                              <option value="">----Select Specialization----</option>
                              <option value="A+">Pharmacist</option>
                              <option value="A-">Dentist</option>
                              <option value="B+">Surgeon</option>
                              <option value="B-">Paediatri</option>
                              <option value="AB+">Lab tech</option>
                            </select>
                          </div>

                          <!-- Add more fields for other profile information -->

                          <button type="submit" name="submit" class="btn btn-primary btn-block">Update Profile</button>
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