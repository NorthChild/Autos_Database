
<?php


session_start();
require_once "pdo.php";

// if we attempt to access delete.php without loggin in
if (! isset($_SESSION['email'])) {

  die("ACCESS DENIED");
}


// here we take the autos_id from the GET
$deleteSelection = $_GET['autos_id'];
error_log("to delete:".$deleteSelection);

############################# Input Validation #################################

$stmt = $pdo -> prepare("SELECT * FROM autos WHERE autos_id = :xyz");
$stmt -> execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt -> fetch(PDO::FETCH_ASSOC);

if ($row === false) {
 $_SESSION['error'] = 'Bad value for user_id';
 header('Location: index.php');
 return;
}

// if we have clicked the delete button, runs the SQL command to delete based on ID
if (isset($_POST['delete'])) {

  $_SESSION['delete'] = $_POST['delete'];

  $stmt = $pdo->prepare("DELETE FROM autos WHERE autos_id = :xyz");
  $stmt->execute(array(":xyz" => $_GET['autos_id']));
  header('Location: index.php');
  return;

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

    <p>Confirm: Deleting <?= htmlentities($row['make']) ?> </p>

    <form method="post">
      <input type="hidden" name="autos_id" value="<?= $row['autos_id'] ?>">
      <button class="nes-btn is-success" type="submit" name="delete" value="Delete">Delete</button>
      <a class="nes-btn is-error" href="index.php">Cancel</a>
    </form>

    </section>

  </body>
</html>
