<?php
session_start();
    include("Includes/connection.php");
    include("Includes/Functions.php");
  
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  
  //Load Composer's autoloader

  ?> 
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="assets/js/sweetalert.min.js"></script>
      <title>Forgot Password</title>
      <link rel="stylesheet" href="assets/css/login.css">
      
  </head>
  <body style="background-image: url(assets/img/adminbg.png); background-size: 100%">
  <div class="container">
      <div class="logo">
          <form class="login-form" action="" method="post">
              <h5 class="text-light alert-light"
                  style="text-align:center; font-size: 25px;padding-bottom: 0px; 10px; margin: 10px">
                  <img src="assets/img/zura.png"
                       alt="" width="150" height="150"
                       class="d-inline-block align-text-top">
                  <br>
                  <span id="zk-trigger"><span>RECOVER PASSWORD</span>
              </h5>
              <div class="form-group">
                  <div class="input-box">
                      <input type="email" name="email" class="form-control form-control-lg"
                             value="<?php if (isset($_POST['submit'])) echo $_POST['email'] ?>"
                             placeholder="Enter Your Email Address"/>
                      <div class="underline"></div>
                  </div>
                  <div class="input-box button">
                      <input type="submit" name="submit" value="Submit">
  
                  </div>
                  <div class="">
                      <a href="login.php">Back to Login</a>
                  </div>
              </div>
          </form>
      </div>
  </div>
<?php
require 'vendor/autoload.php';
  $msg = "";

  if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $code = mysqli_real_escape_string($con, md5(rand()));
    if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
      $query = mysqli_query($con, "UPDATE users SET code='{$code}' WHERE email='{$email}'");

    if ($query) {
    echo "<div style='display: none;'>";
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
  
    try {
      //Server settings
      $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      $mail->Username   = 'wushyyme@gmail.com';                     //SMTP username
      $mail->Password   = 'noelajugepwrrpyi';                               //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
      $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      //Recipients
      $mail->setFrom('wushyyme@gmail.com');
      $mail->addAddress($email);

      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = 'Hello Valued Users,';
      $mail->Body    = 'Greetings! We have received a request to reset your password, If you did not initiate this password reset request, please ignore this email.<br>Here is you reset link!<br><a href="http://localhost/Crimemap/changepassword.php?reset='.$code.'">http://localhost/Crimemap/changepassword.php?reset='.$code.'</a></b>';

      $mail->send();
      echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        echo "</div>";
        echo '<script type="text/javascript">';
        echo 'swal("success!", "Weve sent a reset link to your email", "success").then(function() { window.location = "forgotpassword.php"; });';
        echo '</script>';
        } else{
          echo "</div>";
          echo '<script type="text/javascript">';
          echo 'swal("error!", "invalid email address!", "error").then(function() { window.location = "forgotpassword.php"; });';
          echo '</script>';
        }
     }
    }
  ?>
 
