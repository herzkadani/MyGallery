<?php
$message = 'Sie wurden erfolgreich ausgelogt. Klicken SIe hier um sich erneut anzumelden: <a href="login.php">Login</a>';
$error = 'Sie sind nicht angemeldet. Klicken Sie hier um sich anzumelden: <a href="login.php">Login</a>';


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logout</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  </head>
  <body>
		<div class="container">
        <h1>Logout</h1>
			<?php
				// fehlermeldung oder nachricht ausgeben
				if(!empty($message)){
					echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
				}
        if (!empty($error)) {
          echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } 
			?>
		</div>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	</body>
</html>

