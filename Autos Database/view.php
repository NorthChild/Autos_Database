<?php

session_start();

################################## MODEL ######################################

// Variables
$message = '';
$messageII = '';

$mileNum = false;
$yearNum = false;
$makeSet = false;

## accessing PDO ##

require_once "pdo.php";

############################### User Validation ###############################

// confirmation that userName is logged in
if ( ! isset($_SESSION['name']) ) {
  die('Not logged in');
}


########################### Database Visualisation ############################

 # lets prepare the data from the table to be later displayed in the view
 $sql = "SELECT * FROM autos ORDER BY make";
 $stmt = $pdo -> prepare($sql);
 $stmt -> execute(array());

############################### User Variable #################################

// user to display
$user = $_SESSION['name'];

########################### POST - Redirect - GET #############################

if ( isset($_POST['make']) && isset($_POST['year'])
     && isset($_POST['mileage'])) {
       // POST - redirect - GET
       $make = $_POST['make'];
       $_SESSION['make'] = $_POST['make'];

       $year = $_POST['year'];
       $_SESSION['year'] = $_POST['year'];

       $mileage = $_POST['mileage'];
       $_SESSION['mileage'] = $_POST['mileage'];

}

################################### VIEW ######################################
?>

<!DOCTYPE html>
<html>
<head>
<title>Michael John Carini - Week 4 - Assignment, Autos Database with POST - Redirect </title>

<link rel="stylesheet" href="CSS/autos.css">
<link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
<link href="https://unpkg.com/nes.css/css/nes.css" rel="stylesheet" />

</head>
<body>

<section class="nes-container is-dark" id="gameContainer">

<div class="container">
<h1>Tracking Autos for <?= htmlentities($user) ?></h1>

<p>
  <?php
  // here we set the success / error flash message
  if (isset($_SESSION["success"])) {
    echo('<p style = "color:green">').htmlentities($_SESSION["success"])."</p>\n";
    unset($_SESSION["success"]);
  }
  ?>
</p>


<h2>Automobiles</h2>
<section class="autosDisplay">

  <ul>
  <p class="autosDataBaseDisplayed">
    <?php

    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
      echo("- ".htmlentities($row['make'])." ");
      echo(htmlentities($row['year'])."/");
      echo(htmlentities($row['mileage'])."<br>");
  }

    ?>
  </p>
  </ul>

</section>

<form method="post">
<a class="nes-btn is-success" href="add.php">Add New </a>
<a class="nes-btn is-error" href="logout.php"> Logout </a>
</form>


</div>
</section>

</body>
</html>
