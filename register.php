<?php
$error = '';
$message = '';
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
                <form action="" method="post">
                    <!-- TODO: Clientseitige Validierung: vorname -->
                    <div class="form-group">
                        <label for="firstname">Vorname *</label>
                        <input type="text" name="firstname" class="form-control" id="firstname" maxlength="30" required placeholder="Geben Sie Ihren Vornamen an.">
                    </div>
                    <!-- TODO: Clientseitige Validierung: nachname -->
                    <div class="form-group">
                        <label for="lastname">Nachname *</label>
                        <input type="text" name="lastname" class="form-control" id="lastname" maxlength="30" required placeholder="Geben Sie Ihren Nachnamen an">
                    </div>
                    <!-- TODO: Clientseitige Validierung: email -->
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" name="email" class="form-control" id="email" maxlength="100" required placeholder="Geben Sie Ihre Email-Adresse an.">
                    </div>
                    <!-- TODO: Clientseitige Validierung: benutzername -->
                    <div class="form-group">
                        <label for="username">Benutzername *</label>
                        <input type="text" name="username" class="form-control" id="username" maxlength="30" required placeholder="Gross- und Keinbuchstaben, min 6 Zeichen.">
                    </div>
                    <!-- TODO: Clientseitige Validierung: password -->
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" name="password" class="form-control" id="password" maxlength="255" required placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute">
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