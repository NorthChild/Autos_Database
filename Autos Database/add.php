<?php

session_start();

require_once "pdo.php";

############################### User Validation ###############################

// confirmation that userName is logged in
if ( ! isset($_SESSION['name']) ) {
  die('Not logged in');
}

################################# Cancel #######################################

if (isset($_POST['cancel'])) {

  $_SESSION['cancel'] = $_POST['cancel'];
  header("Location: view.php");
  return;
}

############################# Input Validation #################################

if ( isset($_POST['make']) && isset($_POST['year'])
     && isset($_POST['mileage'])) {

       // POST - redirect - GET
       $_SESSION['make'] = $_POST['make'];
       $_SESSION['year'] = $_POST['year'];
       $_SESSION['mileage'] = $_POST['mileage'];

       // checks for year, make and mileage
       if (is_numeric($_SESSION['year']) === true) {
         $yearNum = true;
         error_log("year input is numeric");
       } else {
         error_log("year input is not numeric");
         $yearNum = false;
       }

       if (is_numeric($_SESSION['mileage']) === true) {
         error_log("mile input is numeric");
         $mileNum = true;
       } else {
         error_log("mile input is not numeric");
         $mileNum = false;
       }

       $makeLen = strlen($_SESSION['make']);

       if ((! isset($_SESSION['make']) || (is_numeric($_SESSION['make']) === true))) {
         error_log($makeLen.": make input not found");
         $makeSet = false;

       } else if ((isset($_SESSION['make']) && (is_numeric($_SESSION['make']) === false))) {
         error_log($makeLen.": make input has been found");
         $makeSet = true;
       }

       // checking whether or not user data is valid input

       if (($makeSet === true) && ($yearNum === false) || ($mileNum === false)) {
         error_log("Mileage and year must be numeric");
         $_SESSION["error"] = 'Mileage and year must be numeric';
         header("Location: add.php");
         return;

       } elseif ((($makeSet === false) || strlen($_SESSION['make']) < 1) && ($yearNum === true) && ($mileNum === true)) {
         error_log("Make is required");
         $_SESSION["error"] = 'Make is required';
         header("Location: add.php");
         return;

       } elseif (($makeSet === true) && ($yearNum === true) && ($mileNum === true)) {

         error_log("Record Inserted");

         $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)');

         $stmt->execute(array(
              ':mk' => $_SESSION['make'],
              ':yr' => $_SESSION['year'],
              ':mi' => $_SESSION['mileage']));

          $_SESSION['success'] = "Record inserted";
          header("Location: view.php");
          return;
       }

 }

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Michael John Carini - Week 4 - Assignment, Autos Database with POST - Redirect </title>

    <link rel="stylesheet" href="CSS/autos.css">
    <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
    <link href="https://unpkg.com/nes.css/css/nes.css" rel="stylesheet" />

  </head>
  <body>
  <section class="nes-container is-dark" id="gameContainer">

  <h1>Tracking Autos for <? htmlentities($_SESSION['account']) ?></h1>


  <?php

  // here we set the success / error flash message
  if (isset($_SESSION["error"])) {
    echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
    unset($_SESSION["error"]);
  }
  ?>


  <form method="post">
  <p>Make:
  <input type="text" name="make"/></p>
  <p>Year:
  <input type="text" name="year"/></p>
  <p>Mileage:
  <input type="text" name="mileage"/></p>

  <button class="nes-btn is-success" type="submit" value="Add">Add </button>
  <button class="nes-btn is-error" type="submit" name="cancel" value="Cancel">Cancel</button>

  </form>

  </ul>
</section>
  </html>
