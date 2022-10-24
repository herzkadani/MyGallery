<?php
$htmloutput = '';
session_start();

include 'dbconnector.inc.php';
//get all assets and loop through them
$query = "SELECT * FROM asset";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $htmloutput.='<div class="asset">
                        <h2>'.htmlspecialchars($row['title']).'</h2>
                        <p>'.htmlspecialchars($row['description']).'</p>
                        </div>';
    }
} 


?>
    <!DOCTYPE html>
    <html lang="de">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title>Home</title>
    </head>

    <body>

        <ul>
            <li><a class="active" href="index.php">Home</a></li>
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) echo '<li><a href="mygallery.php">MyGallery</a></li>'?> <!-- nur wenn eingeloggt -->
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) echo '<li><a href="account.php">Konto</a></li>'?> <!-- nur wenn eingeloggt -->
            <?php if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!==true) echo '<li class="navfloat_right"><a href="register.php">Register</a></li>'?> <!-- nur wenn nicht eingeloggt -->
            <?php if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!==true) echo ' <li class="navfloat_right"><a href="login.php">Login</a></li>'?> <!-- nur wenn nicht eingeloggt -->
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) echo '<li class="navfloat_right"><a href="logout.php">Logout</a></li>'?> <!-- nur wenn eingeloggt -->
        </ul>
        <div class="titlewrapper">
            <h1>Wilkommen bei MyGallery</h1>
            <p>MyGallery ist eine Plattform für Fotografen und Fotobegeisterte. Hier können Sie Ihre Bilder hochladen und mit anderen teilen.</p>
        </div>
        <div class="wrapper">
            <?=$htmloutput?>
        </div>

    </body>

    </html>