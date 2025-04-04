<?php
require('config.php');
session_start();
$errormsg = "";
if (isset($_POST['email'])) {
  $email = stripslashes($_REQUEST['email']);
  $email = mysqli_real_escape_string($con, $email);
  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($con, $password);
  $query = "SELECT * FROM `users` WHERE email='$email'and password='" . md5($password) . "'";
  $result = mysqli_query($con, $query) or die(mysqli_error($con));
  $rows = mysqli_num_rows($result);
  if ($rows == 1) {
    $_SESSION['email'] = $email;
    header("Location: index.php");
  } else {
    $errormsg  = "Wrong";
  }
} else {
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Login</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, rgb(221, 237, 80), rgb(216, 75, 238));
      font-family: 'Arial', sans-serif;
      animation: fadeIn 1s ease-in-out;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    .background-animation {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('moneyeff1.png') repeat;
      animation: falling 10s linear infinite;
      z-index: -1;
      opacity: 0.2;
    }

    @keyframes falling {
      from {
        background-position: 0 0;
      }
      to {
        background-position: 0 100%;
      }
    }

    .login-form {
      width: 380px;
      background: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.4);
      text-align: center;
      animation: slideIn 1s ease-in-out;
      position: relative;
      border: 3px solid rgba(255, 255, 255, 0.6);
    }

    @keyframes slideIn {
      from {
        transform: translateY(-30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .login-form h2 {
      color: #333;
    }

    .form-control {
      border-radius: 20px;
      padding: 10px;
      border: 1px solid #ddd;
    }

    .form-control:focus::placeholder {
      color: transparent;
    }

    .form-control:focus {
      background-color: white;
    }

    .btn-custom {
      background: #ff416c;
      color: #fff;
      border-radius: 20px;
      transition: all 0.3s ease-in-out;
    }

    .btn-custom:hover {
      background: #ff4b2b;
      transform: scale(1.05);
    }

    .hint-text {
      color: #777;
    }
    .hint-text:hover{
      color:#AA336A;
    }
  </style>
</head>

<body>
  <div class="background-animation"></div>
  <div class="login-form">
    <form action="" method="POST" autocomplete="off">
      <h2 class="text-center">C.S.R.D</h2>
      <p class="hint-text">Expense Insight</p>
      <div class="form-group">
        <input type="text" name="email" class="form-control" placeholder="Email" required onfocus="this.removeAttribute('readonly');" readonly>
      </div>
      <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Password" required onfocus="this.removeAttribute('readonly');" readonly>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-custom btn-block" =>Login</button>
      </div>
      <div class="clearfix">
        <label class="float-left form-check-label"><input type="checkbox"> Remember me</label>
      </div>
    </form>
    <p class="text-center">Don't have an account? <a href="register.php" class="text-danger">Register Here</a></p>
  </div>
</body>
<script src="js/jquery.slim.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>