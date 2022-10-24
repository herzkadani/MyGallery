<?php
include 'dbconnector.inc.php';

$htmloutput = '';

//check if user is logged in
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    $error="Sie müssen sich zuerst auf der <a href=\"login.php\">Login-Seite</a> anmelden um auf diese Seite zugreifen zu können.";
    header("Location: login.php");
    die();
}

//get all assets from user and display them
$userid = $_SESSION['user_id'];
$sql = "SELECT * FROM asset WHERE fk_user_id = $userid";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // output data of each row


    while($row = $result->fetch_assoc()) {
        $htmloutput .= '<div class="asset">
                                <h2>'.htmlspecialchars($row['title']).'</h2>
                                <p>'.htmlspecialchars($row['description']).'</p>
                                <form action="asset.php" method="POST">
                                    <input type="hidden" name="asset_id" value="'.$row['id'].'">
                                    <input type="submit" value="Edit" name="edit">
                                    <input type="submit" value="Delete" name="delete">
                            </form>
                        </div>';
    }

} else {
    $htmloutput = "<p style=\"grid-column:1/5; text-align:center;\">Sie haben noch keine Bilder hochgeladen. Lassen Sie sich von anderen Bildern auf der <a href=\"index.php\">Startseite</a> inspirieren.</p>";
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>MyGallery</title>
</head>
<body>

<ul>
  <li><a href="index.php">Home</a></li>
  <li><a class="active" href="mygallery.php">MyGallery</a></li>
  <li><a href="account.php">Konto</a></li>
  <li class="navfloat_right"><a href="logout.php">Logout</a></li>
</ul>
<div class="titlewrapper">
<h1>MyGallery</h1>
<p>Hier können Sie eigene Bilder hochladen. Sie können ihre eigenen Bilder bearbeiten und löschen.</p>
<form action="asset.php" method="POST">
    <input class="createbutton" type="submit" value="+ Hochladen" name="create">
</form>
</div>
<div class="titlewrapper"><h2>Meine Bilder</h2></div>
<div class="wrapper">
<?=$htmloutput?>
</div>
    
</body>
</html>