<?php

$error = '';
$message = '';
$firstname = $lastname = $email = $username = '';
include 'dbconnector.inc.php';
session_start();

if(isset($_SESSION['user_id'])&&$_SESSION['loggedin']){
    $sql = "SELECT * FROM user WHERE id = ".$_SESSION['user_id'];
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    $firstname = $row['vorname'];
    $lastname = $row['nachname'];
    $email = $row['email'];
    $username = $row['username'];
}else{
    $error = 'Sie sind nicht eingeloggt.';
    header("Location: login.php");
    die();
    }

    // Formular wurde gesendet
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    //check if password is correct
    $sql = "SELECT * FROM user WHERE id = ".$_SESSION['user_id'];
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();

    if(password_verify($_POST['password'], $row['password'])){
        //check if new username is already taken
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $_POST['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if($result->num_rows >0 && $row['username'] == $_POST['username'] && $row['id'] != $_SESSION['user_id']){
            $error = 'Dieser Benutzername ist bereits vergeben.';
        }else{

        $sql = "UPDATE user SET vorname = ?, nachname = ?, email = ?, username = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $stmt->bind_param("ssssi", $firstname, $lastname, $email, $username, $_SESSION['user_id']);
        $stmt->execute();
        if($_POST['newpassword'] != ''){
            $sql = "UPDATE user SET password = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $hash = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
            $stmt->bind_param("si", $hash, $_SESSION['user_id']);
            $stmt->execute();
        }

        $message = 'Ihre Daten wurden erfolgreich aktualisiert.';

        }
        
    }else{
        $error .= "Das Passwort ist falsch.<br />";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Konto</title>
</head>
<body>

<ul>
  <li><a href="index.php">Home</a></li>
  <li><a href="mygallery.php">MyGallery</a></li>
  <li><a class="active" href="account.php">Konto</a></li>
  <li class="navfloat_right"><a href="logout.php">Logout</a></li>

</ul><div class="container">
            <h1>Kontodaten ändern</h1>
            <p>
                Hier können Sie ihre Kontodaten ändern. Wenn Sie ihr Passwort nicht ändern wollen, können Sie das Feld "Neues Passwort" leer lassen.
            </p>
            <?php
        // Ausgabe der Fehlermeldungen
        if(empty(!$error)){
          echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } elseif (empty(!$message)){
          echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
        }
      ?>
                <form action="account.php" method="post">
                    <!-- TODO: Clientseitige Validierung: vorname -->
                    <div class="form-group">
                        <label for="firstname">Vorname *</label>
                        <input type="text" name="firstname" class="form-control" id="firstname" value="<?=$firstname?>" maxlength="30" required placeholder="Geben Sie Ihren Vornamen an.">
                    </div>
                    <!-- TODO: Clientseitige Validierung: nachname -->
                    <div class="form-group">
                        <label for="lastname">Nachname *</label>
                        <input type="text" name="lastname" class="form-control" id="lastname" value="<?=$lastname?>" maxlength="30" required placeholder="Geben Sie Ihren Nachnamen an">
                    </div>
                    <!-- TODO: Clientseitige Validierung: email -->
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" name="email" class="form-control" id="email" value="<?=$email?>" maxlength="100" required placeholder="Geben Sie Ihre Email-Adresse an.">
                    </div>
                    <!-- TODO: Clientseitige Validierung: benutzername -->
                    <div class="form-group">
                        <label for="username">Benutzername *</label>
                        <input type="text" name="username" class="form-control" id="username" value="<?=$username?>" maxlength="30" required placeholder="Gross- und Keinbuchstaben, min 6 Zeichen.">
                    </div>
                    <!-- TODO: Clientseitige Validierung: password -->
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" name="password" class="form-control" id="password" maxlength="255" required placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute">
                    </div>
                    <!-- TODO: Clientseitige Validierung: password -->
                    <div class="form-group">
                        <label for="newpassword">Neues Passwort</label>
                        <input type="password" name="newpassword" class="form-control" id="newpassword" maxlength="255" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute">
                    </div>
                    <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
                    <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
                </form>
        </div>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    
</body>
</html>