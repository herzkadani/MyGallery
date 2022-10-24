<?php
// Initialisierung
$error = '';
$firstname = $lastname = $email = $username = '';
$message = '';
//Datenbankverbindung
include('dbconnector.inc.php');

// Wurden Daten mit "POST" gesendet?
if($_SERVER['REQUEST_METHOD'] == "POST"){

 //Validierung aller Felder
  $firstname = trim($_POST['firstname']);
  $lastname = trim($_POST['lastname']);
  $email = trim($_POST['email']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

   if (!(isset($_POST["firstname"]) && !empty(trim($_POST["firstname"])) && strlen(trim($_POST["firstname"]))<=30)) $error .= "Geben Sie bitte einen korrekten Vornamen ein. <br>";
   if (!(isset($_POST["lastname"]) && !empty(trim($_POST["lastname"])) && strlen(trim($_POST["lastname"]))<=30)) $error .= "Geben Sie bitte einen korrekten Nachnamen ein. <br>";
   if (!(isset($_POST["email"]) && !empty(trim($_POST["email"])) && strlen(trim($_POST["email"]))<=100)) $error .= "Geben Sie bitte eine korrekte E-Mail ein. <br>";
   if (!(isset($_POST["username"]) && !empty(trim($_POST["username"])) && 6 <= strlen(trim($_POST["username"])) && strlen(trim($_POST["username"])) <=30)){
        $error .= "Geben Sie bitte einen korrekten Benutzernamen ein. <br>";
   } else{
    //Überprüfe, ob der Username bereits in der Datenbank ist
    $query = "SELECT * FROM user where username=?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result=$stmt->get_result();
		if($result->num_rows){
         $error = $error."Der Benutzername existiert bereits, wählen Sie bitte einen anderen Benutzernamen<br>";
     }
   }
   if (!(isset($_POST["password"]) && !empty(trim($_POST["password"])) && 8 <= strlen($_POST["password"]) && strlen($_POST["password"]) <=255)) $error .= "Geben Sie bitte einen korrektes Passwort ein. <br>";

  // keine Fehler vorhanden
  if(empty($error)){
    $message = "Keine Fehler vorhanden";

    // SQL-Statement erstellen 
    $query = "Insert into user (vorname, nachname, username, password, email) VALUES (?, ?, ?, ?, ?)";
    // SQL-Statement vorbereiten
    $stmt = $mysqli->prepare($query);
    if ($stmt === false) {
        $error .= 'prepare() failed ' . $mysqli->error . '<br />';
    }

    //hash password
    $passwordhash = password_hash($password, PASSWORD_DEFAULT);
    
    // Daten an das SQL-Statement binden
    if (!$stmt->bind_param('sssss', $firstname, $lastname, $username, $passwordhash, $email)) {
        $error .= 'bind_param() failed ' . $mysqli->error . '<br />';
    }
    // SQL-Statement ausführen
    if (!$stmt->execute()) {
        $error .= 'execute() failed ' . $mysqli->error . '<br />';
    }
    $message = "Sie haben sich erfolgreich registriert. Gehen Sie auf die <a href=login.php>Login-Seite</a> um sich einzuloggen.";
}
}
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrierung</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
    <ul>
                <li><a href="index.php">Home</a></li>
                <li class="navfloat_right"><a class="active" href="register.php">Register</a></li>
                <li class="navfloat_right"><a href="login.php">Login</a></li>
            </ul>
        <div class="container">
            <h1>Registrierung</h1>
            <p>
                Bitte registrieren Sie sich, damit Sie diesen Dienst benutzen können.
            </p>
            <?php
        // Ausgabe der Fehlermeldungen
        if(empty(!$error)){
          echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } elseif (empty(!$message)){
          echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
        }
      ?>
                <form action="register.php" method="post">
                    <!-- TODO: Clientseitige Validierung: vorname -->
                    <div class="form-group">
                        <label for="firstname">Vorname *</label>
                        <input type="text" name="firstname" class="form-control" id="firstname" maxlength="30" required placeholder="Geben Sie Ihren Vornamen an." value="<?php echo htmlspecialchars($firstname) ?>">
                    </div>
                    <!-- TODO: Clientseitige Validierung: nachname -->
                    <div class="form-group">
                        <label for="lastname">Nachname *</label>
                        <input type="text" name="lastname" class="form-control" id="lastname" maxlength="30" required placeholder="Geben Sie Ihren Nachnamen an" value="<?php echo htmlspecialchars($firstname) ?>">
                    </div>
                    <!-- TODO: Clientseitige Validierung: email -->
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" name="email" class="form-control" id="email" maxlength="100" required placeholder="Geben Sie Ihre Email-Adresse an." value="<?php echo htmlspecialchars($email) ?>">
                    </div>
                    <!-- TODO: Clientseitige Validierung: benutzername -->
                    <div class="form-group">
                        <label for="username">Benutzername *</label>
                        <input type="text" name="username" class="form-control" id="username" minlength="6" maxlength="30" required placeholder="Gross- und Keinbuchstaben, min 6 Zeichen." value="<?php echo htmlspecialchars($username) ?>">
                    </div>
                    <!-- TODO: Clientseitige Validierung: password -->
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" name="password" class="form-control" id="password" minlength="8" maxlength="255" required placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute">
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