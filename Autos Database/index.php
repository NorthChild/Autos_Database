<?php

session_start();
require_once "pdo.php";

 // here we check if theres any data in the DB to be displayed
 $sql = "SELECT * FROM autos ORDER BY make";
 $stmt = $pdo -> prepare($sql);
 $stmt -> execute(array());

 // here we check if table is empty or not
 $count = $stmt -> rowCount();

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

<?php

// if we havent logged in, the user is presented with this
if (! isset($_SESSION['email'])) {

// framework
echo('<section class="nes-container is-dark">');
echo('<section id="indexContainer" class="message-left">');
echo('<i class="nes-bcrikko"></i>');
echo('<div class="nes-balloon from-left is-dark">');

  echo('<h1>Welcome to the automobiles Database</h1>');
  // here we set the success / error flash message
  if (isset($_SESSION["error"])) {
    echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
    unset($_SESSION["error"]);
  }
  if (isset($_SESSION["success"])) {
    echo('<p style = "color:green">').htmlentities($_SESSION["success"])."</p>\n";
    unset($_SESSION["success"]);
  }

  echo('<a href="login.php">Please log in</a>'."<br>");
  echo('Attempt to <a href="add.php">add data</a> without loggin in');
  return;

  // framework
  echo('</div>');
  echo('</section>');
  echo('</section>');

  // if we have logged in, the user has access to the program
} else {

  // framework
  echo('<section class="nes-container is-dark">');
  echo('<section id="indexContainer" class="message-left">');
  echo('<i class="nes-bcrikko"></i>');
  echo('<div class="nes-balloon from-left is-dark">');


  echo('<h1>Welcome to the automobiles Database</h1>');

  // here we set the success / error flash message
  if (isset($_SESSION["error"])) {
    echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
    unset($_SESSION["error"]);
  }
  if (isset($_SESSION["success"])) {
    echo('<p style = "color:green">').htmlentities($_SESSION["success"])."</p>\n";
    unset($_SESSION["success"]);
  }


  if ($count === 0) {

    echo('No rows found'."<br>"."<br>");

  } else {

    // here we create the table for the DB
    echo('<table border="1">'."\n");
    echo('

      <tr>
        <th> Make </th>
        <th> Model </th>
        <th> Year </th>
        <th> Mileage </th>
        <th> Action </th>
      </tr>
      ');

    while ( $row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
      echo("<tr><td>");
      echo(htmlentities($row['make']));
      echo("</td><td>");
      echo(htmlentities($row['model']));
      echo("</td><td>");
      echo(htmlentities($row['year']));
      echo("</td><td>");
      echo(htmlentities($row['mileage']));
      echo("</td><td>");
      echo('<a href="edit.php?autos_id='.$row['autos_id'].'"> Edit </a> /' );
      echo('<a href="delete.php?autos_id='.$row['autos_id'].'"> Delete </a>' );
      echo("\n</form>\n");
      echo("</td></tr>");
    }
    echo('</table>'."<br>");
  }

   // New entry and Logout
   echo('<a href="add.php">Add New Entry</a>'."<br>");
   echo('<a href="logout.php">Logout</a>');

}

// framework
echo('</div>');
echo('</section>');
echo('</section>');

?>

  </body>
</html>
