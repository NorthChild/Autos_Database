<?php

session_start();
require_once "pdo.php";

// if we attempt to access add.php without loggin in
if (! isset($_SESSION['email'])) {

  die("ACCESS DENIED");
}

############################# Input Validation #################################

if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year'])
     && isset($_POST['mileage'])) {

       // POST - redirect - GET
       $_SESSION['make'] = $_POST['make'];
       $_SESSION['model'] = $_POST['model'];
       $_SESSION['year'] = $_POST['year'];
       $_SESSION['mileage'] = $_POST['mileage'];

       // checks for year and mileage
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

       // here we check for make and model
       $makeLen = strlen($_SESSION['make']);
       $modelLen = strlen($_SESSION['model']);

       // here we check if make and model are set
       if ((! isset($_SESSION['make']))) {
         error_log($makeLen.": make input not found");
         $makeSet = false;

       } else if ((isset($_SESSION['make']))) {
         error_log($makeLen.": make input has been found");
         $makeSet = true;
       }

       if ((! isset($_SESSION['model']))) {
         error_log($makeLen.": model input not found");
         $modelSet = false;

       } else if ((isset($_SESSION['model']))) {
         error_log($makeLen.": model input has been found");
         $modelSet = true;
       }

       // checking whether or not user data is valid input

      if ((strlen($makeSet) < 1) || (strlen($modelSet) < 1) || (strlen($yearNum) < 1) || (strlen($mileNum) < 1)) {

        error_log("All fields are required");
        $_SESSION["error"] = 'All fields are required';
        header("Location: add.php");
        return;

       } elseif (($makeSet === true) && ($modelSet === true) && ($yearNum === false) || ($mileNum === false)) {

         error_log("Mileage and year must be numeric");
         $_SESSION["error"] = 'Mileage and year must be numeric';
         header("Location: add.php");
         return;

       } elseif ((($makeSet === false) || strlen($_SESSION['make']) < 1) && ($makeSet === true) && ($yearNum === true) && ($mileNum === true)) {

         error_log("Make is required");
         $_SESSION["error"] = 'Make is required';
         header("Location: add.php");
         return;

       } elseif ((($modelSet === false) || strlen($_SESSION['model']) < 1) && ($makeSet === true) && ($yearNum === true) && ($mileNum === true)) {

         error_log("Model is required");
         $_SESSION["error"] = 'Model is required';
         header("Location: add.php");
         return;

         // all correct, we proceed into inserting the data and redirecting
       } elseif (($makeSet === true) && ($makeSet === true) && ($yearNum === true) && ($mileNum === true)) {

         error_log("Record Inserted");

         $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage) VALUES ( :mk, :mo, :yr, :mi)');

         $stmt->execute(array(
              ':mk' => $_SESSION['make'],
              ':mo' => $_SESSION['model'],
              ':yr' => $_SESSION['year'],
              ':mi' => $_SESSION['mileage']));

          $_SESSION['success'] = "Record added";
          header("Location: index.php");
          return;
       }
 }



?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Michael John Carini - Assignment Autos CRUD</title>
    <link rel="stylesheet" href="CSS/autosCSS.css">
    <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
    <link href="https://unpkg.com/nes.css/css/nes.css" rel="stylesheet" />
  </head>
  <body>

  <section class="nes-container is-dark" id="gameContainer">

    <h1>Tracking automobiles for <?= $_SESSION['email'] ?></h1>

    <?php
    // here we set the success / error flash message
    if (isset($_SESSION["error"])) {
      echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
      unset($_SESSION["error"]);
    }
    ?>

    <form method="post">
      <p>Make: <input type="text" name="make"> </p>
      <p>Model: <input type="text" name="model"> </p>
      <p>Year: <input type="text" name="year"> </p>
      <p>Mileage: <input type="text" name="mileage"> </p>

      <button class="nes-btn is-success" type="submit" value="Add New">Add New</button> <a class="nes-btn is-error" id="addCancelButton" href="index.php">Cancel</a>

    </form>

    </section>

  </body>
</html>
