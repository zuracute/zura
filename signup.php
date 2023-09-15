<?php
session_start();
  include("includes/connection.php");
  include("includes/functions.php");
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  //Load Composer's autoloader
  ?> <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="assets/js/sweetalert.min.js"></script>
      <link rel="stylesheet" href="assets/css/signup.css">
      <title>Register</title>
  </head>
  <body>
      <div class="container">
          <div class="logo">
              <img src="assets/img/zura.png" alt="Logo" width="90" height="90">
          </div>
          <form class="signup-form" action="signup.php" method="post">
              <h1>Create Account</h1>
              <div class="input-box">
                  <input type="text" name="firstname" placeholder="First Name" required>
                  <div class="underline"></div>
              </div>
              <div class="input-box">
                  <input type="text" name="MI" placeholder="Middle Initial" required>
                  <div class="underline"></div>
              </div>
              <div class="input-box">
                  <input type="text" name="lastname" placeholder="Last Name" required>
                  <div class="underline"></div>
              </div>
              <div class="input-box">
                  <input type="email" name="email" placeholder="Email" required>
                  <div class="underline"></div>
              </div>
              <div class="input-box">
                  <input type="password" name="password" placeholder="Password" required>
                  <div class="underline"></div>
              </div>
              <div class="input-box">
                  <input type="password" name="cpassword" placeholder="Confirm Password" required>
                  <div class="underline"></div>
              </div>
              <div class="input-box button">
                  <input type="submit" name="submit" value="Sign Up">
              </div>
              <div class="login-link">
                  Already have an account? <a href="Login.php">Log In</a>
              </div>
          </form>
      </div>
  </body>
  </html>
  

<?php
require 'vendor/autoload.php';
  $msg = "";
  if (isset($_POST['submit'])) {
    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
    $MI = mysqli_real_escape_string($con, $_POST['MI']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    $code = mysqli_real_escape_string($con, md5(rand()));
  
    if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
        echo "</div>";
          echo '<script type="text/javascript">';
          echo 'swal("error!", "SORRY KA MERON NA!", "error").then(function() { window.location = "signup.php"; });';
          echo '</script>';
    } else {
      if ($password === $cpassword) {
        $sql = "INSERT INTO users (firstname, lastname, MI, email, password, code, status) VALUES  ('{$firstname}', '{$lastname}', '{$MI}',  '{$email}', '{$password}', '{$code}' ,'active')";
        echo"</i>
        </div>";

        $class1 = '"preview-icon bg-success"';
        $class2 = '"ti-info-alt mx-0"';
        $code_msg = 
        "<div class=$class1> 
        <i class=$class2> 
        </i> 
        </div>";

        $code_msg = mysqli_real_escape_string($con, $code_msg);

        $msg = "User registration!";
        $msg_disp = "A user has succesfully registered!";
        $Now = new DateTime('now', new DateTimeZone('Asia/Taipei'));
        $Now = $Now->format('Y-m-d H:i:s');
        $sql = "INSERT INTO users (firstname, lastname, MI, email, password, code, status) VALUES  ('{$firstname}', '{$lastname}', '{$MI}',  '{$email}', '{$password}', '{$code}' ,'active')";
        $result = mysqli_query($con, $sql);

        if ($result) {
          // success message
        } else {
          // error message
        }

        
  
        if ($result) {
           echo "<div style='display: none;'>";
          //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'wushyyme@gmail.com';                   //SMTP username
            $mail->Password   = 'noelajugepwrrpyi';                     //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            //Recipients
            $mail->setFrom('wushyyme@gmail.com');
            $mail->addAddress($email);
           //Content
          
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Crime Map Account Verification';
            $mail->Body = 'Greetings! Welcome to Crime Map! In order to access your account, please verify your account.
            <span style="display: inline-block; background-color: #3498db; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 16px;">
            <a href="http://localhost/Crimemap/Login.php?verification=' . $code . '" style="color: inherit; text-decoration: none;">Verify Your Account</a>
            </span>';
            $mail->send();
            echo 'Message has been sent';


        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        echo "</div>";
        echo '<script type="text/javascript">';
        echo 'swal("success!", "We have sent a verification to your email", "success").then(function() { window.location = "signup.php"; });';
        echo '</script>';
        } else{
          echo "</div>";
          echo '<script type="text/javascript">';
          echo 'swal("error!", "wrong ka!", "error").then(function() { window.location = "signup.php"; });';
          echo '</script>';
        }
      } else {
        echo "</div>";
          echo '<script type="text/javascript">';
          echo 'swal("error!", "wrong ka!", "error").then(function() { window.location = "signup.php"; });';
          echo '</script>';
     
         }  
        }
      }
  ?>