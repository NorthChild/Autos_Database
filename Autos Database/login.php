
<?php

session_start();

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

// logout to index
if (isset($_POST['cancel']) && $_POST['cancel'] === "Cancel") {
  header('Location: index.php');
  return;
}

// validate the data
if (isset($_POST['email']) && isset($_POST['pass'])) {

  # insert POST DATA into Session variables
  $email = $_POST['email'];
  $_SESSION['name'] = $email;

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
    header("Location: view.php?email=".urlencode($email));
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
    <link rel="stylesheet" href="CSS/autos.css">
    <title>Michael John Carini - Week 4 - Assignment, Autos Database with POST - Redirect </title>

    <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
    <link href="https://unpkg.com/nes.css/css/nes.css" rel="stylesheet" />

  </head>
  <body>

    <section class="nes-container is-dark">
      <section id="indexContainer" class="message -right">
        <div class="nes-balloon from-right is-dark">

          <div id="loginContainer">
              <h1 id="loginH1">Please Log In</h1>

              <p>
                <?php

                // here we set the success / error flash message
                if (isset($_SESSION["error"])) {
                  echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
                  unset($_SESSION["error"]);
                }

                ?>
              </p>

              <form method="post">
                  <label for="name"><b>User Name:</b></label>
                  <input type="text" name="email" id="name"> <br>

                  <label><b>Password:</b></label>
                  <input type="password" name="pass" > <br>

                  <div class="buttons">
                    <input class="nes-btn is-success" id="submit" size="30" type="submit" value="Log In">
                    <input class="nes-btn is-error" id="submit" size="30" type="submit" name="cancel" value="Cancel"  >
                  </div>
              </form>
                <p class="hint">
                For a password hint, view source and find a password hint
                in the HTML comments.
                <!-- Hint: The password is the three character name of the
                programming language used in this class (all lower case)
                followed by 123. -->
                </p>
          </div>

        </div>
        <i id="secondBrikko" class="nes-bcrikko"></i>
      </section>

    </section>

  </body>
</html>
