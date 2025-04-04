<?php
require('config.php');
if (isset($_REQUEST['firstname'])) {
  if ($_REQUEST['password'] == $_REQUEST['confirm_password']) {
    $firstname = stripslashes($_REQUEST['firstname']);
    $firstname = mysqli_real_escape_string($con, $firstname);
    $lastname = stripslashes($_REQUEST['lastname']);
    $lastname = mysqli_real_escape_string($con, $lastname);

    $email = stripslashes($_REQUEST['email']);
    $email = mysqli_real_escape_string($con, $email);


    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);


    $trn_date = date("Y-m-d H:i:s");

    $query = "INSERT into `users` (firstname, lastname, password, email, trn_date) VALUES ('$firstname','$lastname', '" . md5($password) . "', '$email', '$trn_date')";
    $result = mysqli_query($con, $query);
    if ($result) {
      header("Location: login.php");
    }
  } else {
    echo ("ERROR: Please Check Your Password & Confirmation password");
  }
}
?>
<!DOCTYPE html>
< lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Register</title>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
    background: url('regbg.jpg') no-repeat center center/cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    }

    .signup-form {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
        transform: scale(0.8);
        animation: popUp 0.5s forwards;
    }

    @keyframes popUp {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* Hover Effect on Input Fields */
    .form-control:hover {
        border-color: #ff5733;
        box-shadow: 0px 0px 10px rgba(255, 87, 51, 0.5);
    }

    /* Password Eye Icon */
    .password-container {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #999;
    }

    .toggle-password:hover {
        color: #333;
    }


    .form-control {
      height: 40px;
      box-shadow: none;
      color: #969fa4;
    }

    .form-control:focus {
      border-color: #5cb85c;
    }

    .form-control,
    .btn {
      border-radius: 3px;
    }

    .signup-form {
      width: 450px;
      margin: 0 auto;
      padding: 30px 0;
      font-size: 15px;
    }

    .signup-form h2 {
      color: #636363;
      margin: 0 0 15px;
      position: relative;
      text-align: center;
    }

    .signup-form h2:before,
    .signup-form h2:after {
      content: "";
      height: 2px;
      width: 30%;
      background: #d4d4d4;
      position: absolute;
      top: 50%;
      z-index: 2;
    }

    .signup-form h2:before {
      left: 0;
    }

    .signup-form h2:after {
      right: 0;
    }

    .signup-form .hint-text {
      color: #999;
      margin-bottom: 30px;
      text-align: center;
    }

    .signup-form form {
      color: #999;
      border-radius: 3px;
      margin-bottom: 15px;
      background: #fff;
      box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
      padding: 30px;
      border: 1px solid #ddd;
    }

    .signup-form .form-group {
      margin-bottom: 20px;
    }

    .signup-form input[type="checkbox"] {
      margin-top: 3px;
    }

    .signup-form .btn {
      font-size: 16px;
      font-weight: bold;
      min-width: 140px;
      outline: none !important;
    }

    .signup-form .row div:first-child {
      padding-right: 10px;
    }

    .signup-form .row div:last-child {
      padding-left: 10px;
    }

    .signup-form a:hover {
      text-decoration: none;
    }

    .signup-form form a {
      color: #5cb85c;
      text-decoration: none;
    }

    .signup-form form a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="signup-form">
    <form action="" method="POST" autocomplete="off">
      <h2>Register</h2>
      <div class="form-group">
        <div class="row">
          <div class="col"><input type="text" class="form-control" name="firstname" placeholder="First Name" required="required"></div>
          <div class="col"><input type="text" class="form-control" name="lastname" placeholder="Last Name" required="required"></div>
        </div>
      </div>
      <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="Email" required="required">
      </div>
      <div class="form-group password-container">
          <input type="password" class="form-control password-input" name="password" placeholder="Password" required="required">
          <i class="bi bi-eye-slash toggle-password"></i>
      </div>
      <div class="form-group password-container">
          <input type="password" class="form-control password-input" name="confirm_password" placeholder="Confirm Password" required="required">
          <i class="bi bi-eye-slash toggle-password"></i>
      </div>

      <div class="form-group">
          <label class="form-check-label">
              <input type="checkbox" required="required">
              I accept the <a href="#" id="terms-link">Terms of Use</a> &amp;
              <a href="#" id="privacy-link">Privacy Policy</a>
          </label>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-danger btn-lg btn-block" style="border-radius:0%;">Register</button>
      </div>
    </form>
    <div class="text-center">Already have an account? <a class="text-success" href="login.php">Login Here</a></div>
  </div>

  <!-- Terms & Privacy Modal -->
<div id="termsModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Disclaimer</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>This is just a demo project. The Terms of Use & Privacy Policy are for demonstration purposes only.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</body>
<!-- Bootstrap core JavaScript -->
<script src="js/jquery.slim.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- Croppie -->
<script src="js/profile-picture.js"></script>
<!-- Menu Toggle Script -->
<script>
  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
</script>
<script>
  feather.replace()
</script>
<script>
document.querySelectorAll(".toggle-password").forEach(item => {
    item.addEventListener("click", function() {
        let input = this.previousElementSibling;
        if (input.type === "password") {
            input.type = "text";
            this.classList.replace("bi-eye-slash", "bi-eye");
        } else {
            input.type = "password";
            this.classList.replace("bi-eye", "bi-eye-slash");
        }
    });
});
</script>

<script>
document.getElementById("terms-link").addEventListener("click", function(event) {
    event.preventDefault();
    $("#termsModal").modal("show");
});

document.getElementById("privacy-link").addEventListener("click", function(event) {
    event.preventDefault();
    $("#termsModal").modal("show");
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</html>