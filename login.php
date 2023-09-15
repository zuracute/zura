<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");

if (isset($_GET['verification'])) {
  if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE code='{$_GET['verification']}'")) > 0) {
    $query = mysqli_query($con, "UPDATE users SET code='' WHERE code='{$_GET['verification']}'");
    if ($query) {
      $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
    }
  } else {
    header("Location: login.php");
    exit;
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN </title>
    <script src="assets/js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body  style="background-image: url(assets/img/adminbg.png);  background-size: 100%">
  <div class="container">
  <div class="logo">
    
  <form class="login-form" action="login.php" method="post">
    <h5 class="text-light alert-light" style="text-align:center; font-size: 25px;padding-bottom: 0px; 10px; margin: 10px">
    <a href="admin-1/Admin.php"> <img src="assets/img/zura.png"  alt="" width="150" height="150" class="d-inline-block align-text-top">
    </a>
      <br>
      <span id="zk-trigger"><span id="hide1" class="hide">CRIME MAPPING</span>
      <?php if (isset($_SESSION['error'])) { ?>
      <p class="error"><?php echo $_SESSION['error']; ?></p>
      <?php unset($_SESSION['error']); ?>
     <?php } ?>

    </h5>
    <div class="form-group">
      <div class="input-box">
        <input type="email" name="email" placeholder="Email" required>
        <div class="underline"></div>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Enter Your Password" required>
        <div class="underline"></div>
      </div>
      <div class="input-box button">
        <input type="submit" name="Submit" value="login">
      </div>
      <div class="links">
          <a href="signup.php">Click here to Sign up</a>
          <a href="forgotpassword.php" style="float:right;">Forgotten password?</a>

          
      </div>
  </form>
 <?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Something was posted
  $email = $_POST['email'];
  $password = $_POST['password'];
  // $firstname = $_POST['firstname'];
  // $lastname = $_POST['lastname'];
  if (!empty($email) && !empty($password) && !is_numeric($email)) {
    // Read from the database
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($con, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
      $user_data = mysqli_fetch_assoc($result);

      if ($user_data['code'] == '') {
        if ($user_data['status'] == 'active') { // Check if account is active
          if ($user_data['password'] === $password) {
            $_SESSION['id'] = $user_data['id'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['firstname'] = $user_data['firstname'];
            $_SESSION['lastname'] = $user_data['lastname'];
            header("Location: index.php");
            exit;
                  } else {
                      echo '<script type="text/javascript">';
                      echo 'swal("Error!", "Wrong email or password", "error").then(function() { window.location = "login.php"; });';
                      echo '</script>';
                      exit;
                  }
              } else {
                  echo '<script type="text/javascript">';
                  echo 'swal("Error!", "Your account has been deactivated", "error").then(function() { window.location = "login.php"; });';
                  echo '</script>';
                  exit;
              }
          } else {
              echo '<script type="text/javascript">';
              echo 'swal("Error!", "Your account has not been verified yet", "error").then(function() { window.location = "login.php"; });';
              echo '</script>';
              exit;
          }
      }
  }
} 
