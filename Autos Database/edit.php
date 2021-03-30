<?php

session_start();
require_once "pdo.php";

// if we attempt to access edit.php without loggin in
if (! isset($_SESSION['email'])) {

  die("ACCESS DENIED");
}

############################# Input Validation #################################

$stmt = $pdo -> prepare("SELECT * FROM autos WHERE autos_id = :xyz");
$stmt -> execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt -> fetch(PDO::FETCH_ASSOC);

if ($row === false) {
 $_SESSION['error'] = 'Bad value for user_id';
 header('Location: index.php');
 return;
}

$mk = htmlentities($row['make']);
$mo = htmlentities($row['model']);
$yr = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);


if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year'])
     && isset($_POST['mileage'])) {

       // POST - redirect - GET
       $_SESSION['make'] = $_POST['make'];
       $_SESSION['model'] = $_POST['model'];
       $_SESSION['year'] = $_POST['year'];
       $_SESSION['mileage'] = $_POST['mileage'];

       // checks for year and mileage
       $yearLen = strlen($_SESSION['year']);
       $mileLen = strlen($_SESSION['mileage']);
       // error_log("str len of year:".strlen($yearLen));

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

      if ((strlen($makeSet) < 1) || (strlen($modelSet) < 1) || ($yearLen < 1) || ($mileLen < 1)) {

        error_log("All fields are required");
        error_log("str len of year:".strlen($yearNum));
        $_SESSION["error"] = 'All fields are required';
        header("Location: edit.php?autos_id=".$_REQUEST['autos_id']);
        return;

      } elseif (($makeSet === true) && ($modelSet === true) && ($yearNum != true) || ($mileNum != true)) {

         error_log("Mileage and year must be numeric");
         $_SESSION["error"] = 'Mileage and year must be numeric';
         header("Location: edit.php?autos_id=".$_REQUEST['autos_id']);
         return;

       } elseif ((($makeSet === false) || strlen($_SESSION['make']) < 1) && ($makeSet === true) && ($yearNum === true) && ($mileNum === true)) {

         error_log("Make is required");
         $_SESSION["error"] = 'Make is required';
         header("Location: edit.php?autos_id=".$_REQUEST['autos_id']);
         return;

       } elseif ((($modelSet === false) || strlen($_SESSION['model']) < 1) && ($makeSet === true) && ($yearNum === true) && ($mileNum === true)) {

         error_log("Model is required");
         $_SESSION["error"] = 'Model is required';
         header("Location: edit.php?autos_id=".$_REQUEST['autos_id']);
         return;

         // all correct, we proceed into updating the data and redirecting
       } elseif (($makeSet === true) && ($makeSet === true) && ($yearNum === true) && ($mileNum === true)) {

         error_log("Record Updated");

         $sql = "UPDATE autos SET make = :make, model = :model, year = :year, mileage = :mileage WHERE autos_id = :autos_id";
         $stmt = $pdo -> prepare($sql);
         $stmt -> execute(array(
           ':make' => $_POST['make'],
           ':model' => $_POST['model'],
           ':year' => $_POST['year'],
           ':mileage' => $_POST['mileage'],
           ':autos_id' => $_GET['autos_id'],
         ));


         $_SESSION['success'] = 'Record updated';
         header('Location: index.php');
         return;
       }
 }

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

    <section class="nes-container is-dark" id="gameContainer">

    <h1>Editing Automobile</h1>

    <?php
    // here we set the success / error flash message
    if (isset($_SESSION["error"])) {
      echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
      unset($_SESSION["error"]);
    }
    ?>

    <form method="post">
    <p>Make:
    <input type="text" name="make" value="<?= $mk ?>"></p>
    <p>Model:
    <input type="text" name="model" value="<?= $mo ?>"></p>
    <p>Year:
    <input type="text" name="year" value="<?= $yr ?>"></p>
    <p>Mileage:
    <input type="text" name="mileage" value="<?= $mi ?>"></p>
    <p>
    <button class="nes-btn is-success" type="submit" value="Save"/>Save</button>
    <a class="nes-btn is-error" href="index.php">Cancel</a>
    </p>
    </form>

  </section>

  </body>
</html>
