<?php
include("include/connect.php");

$firstname_err = null;
$lastname_err = null;
$othername_err = null;
$email_err = null;
$password_err = null;

if (isset($_POST['submit'])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $othername = $_POST["othername"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    //VALIDATION
    $firstname = htmlspecialchars($firstname);
    $lastname = htmlspecialchars($lastname);
    $othername = htmlspecialchars($othername);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($password);

    $password_length = strlen($password);

    //FETCHING ALREADY REGISTERED EMAIL
    $fetch_email = mysqli_query($connect, "SELECT * FROM `student` WHERE email = '$email'");

    //FETCHING ALREADY REGISTERED MATRIC NUMBER
    $fetch_matric = mysqli_query($connect, "SELECT * FROM `student` WHERE email = '$email'");


    //VALIDATION 2
    if (!preg_match('/^[a-zA-Z]+$/u', $firstname) || !preg_match('/^[a-zA-Z]+$/u', $lastname) || !filter_var($email, FILTER_VALIDATE_EMAIL) || mysqli_num_rows($fetch_email) > 0 || !preg_match('/^[a-zA-Z0-9]+$/u', $password) || $password_length < 6) {

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

        if (mysqli_num_rows($fetch_matric) > 0) {
            $email_err = "Email address already registered.";
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/u', $password)) {
            $password_err = "Invalid Password.";
        }

        if ($password_length < 6) {
            $password_err = "Password must be up to six characters";
        }
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = mysqli_query($connect, "INSERT INTO `student`(firstname,lastname,othername,email,password) VALUES('$firstname','$lastname','$othername','$email','$password_hash')");

        if ($sql) {
            echo "<script>
            alert('Signup successful!');
            window.location.href='signin.php';
        </script>";
        } else {
            $connect_error = mysqli_error($connect);
            echo "<script>
            alert('Signup error:" . $connect_error . "');
        </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Appointment Scheduler | Student's Sign-up</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="./Bootstrap/bootstrap.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl position-relative bg-light d-flex p-0">

        <!-- Sign Up Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-5">
                    <div class="bg-white rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-center flex-column mb-3">
                            <a href="index.php" class="">
                                <h3 style="color: #1D62F0;">Appointment Scheduler</h3>
                            </a>
                            <h3>Sign Up</h3>
                        </div>
                        <form action="student-signup.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="text" name="firstname" class="form-control" id="floatingText" placeholder="First Name" required>
                                <label for="floatingText">First Name</label>
                                <p class="text-danger"><?php echo $firstname_err; ?></p>
                            </div>


                            <div class="form-floating mb-3">
                                <input type="text" name="lastname" class="form-control" id="floatingText" placeholder="Last Name" required>
                                <label for="floatingText">Last Name</label>
                                <p class="text-danger"><?php echo $lastname_err; ?></p>

                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="othername" class="form-control" id="floatingText" placeholder="Other Name">
                                <label for="floatingText">Other Name</label>
                                <p class="text-danger"><?php echo $othername_err; ?></p>

                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                                <label for="floatingInput">Email address</label>
                                <p class="text-danger"><?php echo $email_err; ?></p>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                                <label for="floatingPassword">Password</label>
                                <p class="text-danger"><?php echo $password_err; ?></p>

                            </div>

                            <button type="submit" name="submit" class="btn btn-primary py-3 w-100 mb-4" style="background-color: #1D62F0;color:white;">Sign Up</button>
                        </form>
                        <p class="text-center mb-0">Already have an Account? <a href="./signin.php">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign Up End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="./Bootstrap/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>