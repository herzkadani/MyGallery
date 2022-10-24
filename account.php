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
    $firstname = htmlspecialchars($row['vorname']);
    $lastname = htmlspecialchars($row['nachname']);
    $email = htmlspecialchars($row['email']);
    $username = htmlspecialchars($row['username']);
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

        if (!(!empty($firstname) && strlen($firstname)<=30)) $error .= "Geben Sie bitte einen korrekten Vornamen ein. <br>";
        if (!(!empty($lastname) && strlen($lastname)<=30)) $error .= "Geben Sie bitte einen korrekten Nachnamen ein. <br>";
       if (!(!empty($_email) && strlen($email)<=100 && preg_match(" /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/ ", $email))) $error .= "Geben Sie bitte eine korrekte E-Mail-Adresse ein. <br>";
       if (!(trim($username) && preg_match(" /(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,30}/ ", $username))) {
        $error .= "Geben Sie bitte einen korrekten Benutzernamen ein. <br>";
       } else{
        //check if new username is already taken
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $_POST['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if($result->num_rows >0 && $row['username'] == $_POST['username'] && $row['id'] != $_SESSION['user_id']){
            $error .= 'Dieser Benutzername ist bereits vergeben.';
        }

        }
        
       }else{
        $error .= "Das Passwort ist falsch.<br />";
    }
       
    if(empty($error)){
        
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
                    <div class="form-group">
                        <label for="firstname">Vorname *</label>
                        <input type="text" name="firstname" class="form-control" id="firstname" value="<?=$firstname?>" maxlength="30" required placeholder="Geben Sie Ihren Vornamen an.">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Nachname *</label>
                        <input type="text" name="lastname" class="form-control" id="lastname" value="<?=$lastname?>" maxlength="30" required placeholder="Geben Sie Ihren Nachnamen an">
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" name="email" class="form-control" id="email" value="<?=$email?>" maxlength="100" required placeholder="Geben Sie Ihre Email-Adresse an.">
                    </div>
                    <div class="form-group">
                        <label for="username">Benutzername *</label>
                        <input type="text" name="username" class="form-control" id="username" value="<?=$username?>" maxlength="30" required placeholder="Gross- und Keinbuchstaben, min 6 Zeichen." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,30}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" name="password" class="form-control" id="password" maxlength="255" required placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,255}">
                    </div>
                    <div class="form-group">
                        <label for="newpassword">Neues Passwort</label>
                        <input type="password" name="newpassword" class="form-control" id="newpassword" maxlength="255" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,255}">
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