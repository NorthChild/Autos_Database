<?php

session_start();
require_once "pdo.php";

###################################### MODEL ######################################

// variables
$email = '';
$userPass = '';
$message = false;
$emailCheck = false;

$salt = 'XyZzy12*_';
$md5 = hash('md5', 'XyZzy12*_php123');

$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';

################################ Validation #####################################


// validate the data
if (isset($_POST['email']) && isset($_POST['pass'])) {

  # insert POST DATA into Session variables
  $email = $_POST['email'];
  $_SESSION['email'] = $email;

  $userPass = $_POST['pass'];
  $_SESSION['pass'] = $userPass;

  # concatenate the password plus the salt
  $passPlusSalt = $salt.$userPass;

  ## here we check for the email validation ##
  $passCheck = hash('md5', $passPlusSalt);

  ## here we check for the email validation ##
  $emailCheck = strpos($email, '@');

################################ Validation #####################################

  # validation of the data using nested series of if/elseif statements
  if (strlen($email) < 1 || strlen($userPass) < 1) {
    $_SESSION['error'] = 'User Name and Password are required.';

  } elseif (($passCheck != $stored_hash) && ($emailCheck != false)) {
    error_log("Login fail ".$_POST['email']." $passCheck");
    $_SESSION["error"] = "Incorrect Password.";
    header("Location: login.php");
    return;

  } elseif (($passCheck === $stored_hash) && ($emailCheck === false)) {
    error_log("Login fail ".$_POST['email']." $passCheck");
    $_SESSION["error"] = "Email must have an at-sign (@)";
    header("Location: login.php");
    return;

  } elseif (($passCheck != $stored_hash) && ($emailCheck === false)) {
    error_log("Login fail ".$_POST['email']." $passCheck");
    $_SESSION['error'] = 'Both Email and Password are incorrect';
    header("Location: login.php");
    return;

  } elseif (($passCheck === $stored_hash) && ($emailCheck != false)) {
    header("Location: index.php?email=".urlencode($email));
    error_log("Login success ".$_POST['email']);
    return;
  }

}

###################################### VIEW #######################################
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Michael John Carini - Autos DB CRUD</title>
    <link rel="stylesheet" href="CSS/autosCSS.css">
    <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
    <link href="https://unpkg.com/nes.css/css/nes.css" rel="stylesheet" />
  </head>
  <body>


<section class="nes-container is-dark">
<section id="indexContainer" class="message -right">
<div class="nes-balloon from-right is-dark">
<div id="loginContainer">

<h1>Please Log In</h1>


<p>
  <?php

  // here we set the error flash message
  if (isset($_SESSION["error"])) {
    echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
    unset($_SESSION["error"]);
  }

  ?>
</p>


<form class="" method="post">
  <label> <b>User Name</b><input type="text" name="email"> </label> <br>
  <label> <b>Password</b><input type="password" name="pass"> </label> <br>
  <button class="nes-btn is-success" type="submit" value="Log In">Log In</button> <a class="nes-btn is-error" href="index.php">Cancel</a>
</form>


</div>

  </div>
    <i id="secondBrikko" class="nes-bcrikko"></i>
  </section>

</section>

  </body>
</html>
