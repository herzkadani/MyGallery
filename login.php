<?php
	
//Datenbankverbindung
include('dbconnector.inc.php');

$error = '';
$message = '';
$username = '';


// Formular wurde gesendet und Besucher ist noch nicht angemeldet.
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	// username
	if(isset($_POST['username'])){
		//trim
		$username = trim($_POST['username']);
		
		// prüfung benutzername
		if(empty($username) || !preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,30}/", $username)){
			$error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";
		}
	} else {
		$error .= "Geben Sie bitte den Benutzername an.<br />";
	}
	// password
	if(isset($_POST['password'])){
		//trim
		$password = trim($_POST['password']);
		// passwort gültig?
		if(empty($password) || !preg_match("/(?=^.{8,255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)){
			$error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
		}
	} else {
		$error .= "Geben Sie bitte das Passwort an.<br />";
	}
	
	// kein fehler
	if(empty($error)){

		$query = "SELECT * FROM user where username=?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		
		// Passwort auslesen und mit dem eingegeben Passwort vergleichen
		$result=$stmt->get_result();
		if($result->num_rows){
			while($row = $result->fetch_assoc()){
				if(password_verify($password, $row['password'])){
					$message = "Anmeldung erfolgreich. <br />";
					session_start();
					$_SESSION['username'] = $username;
					$_SESSION['user_id'] = $row['id'];
					$_SESSION['loggedin'] = true;
					session_regenerate_id(true);
					header("Location: mygallery.php");
					die();
				}else{
					$error .= "Benutzername oder Passwort ist falsch. Falls Sie noch kein Benutzerkonto haben können Sie sich <a href=\"register.php\">hier Registrieren.</a><br /> ";
				}
			}
		}else{
			$error .= "Benutzername oder Passwort ist falsch. Falls Sie noch kein Benutzerkonto haben können Sie sich <a href=\"register.php\">hier Registrieren.</a><br /> ";
		}
		
		$result->free();
		
	}
}
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="style.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	
<ul>
  <li><a href="index.php">Home</a></li>
  <li class="navfloat_right"><a href="register.php">Register</a></li>
  <li class="navfloat_right"><a class="active" href="login.php">Login</a></li>
</ul>
		<div class="container">
			<h1>Login</h1>
			<p>
				Bitte melden Sie sich mit Benutzernamen und Passwort an.
			</p>
			<?php
				// fehlermeldung oder nachricht ausgeben
				if(!empty($message)){
					echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
				} else if(!empty($error)){
					echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
				}
			?>
			<form action="" method="POST">
				<div class="form-group">
					<label for="username">Benutzername *</label>
					<input type="text" name="username" class="form-control" id="username"
						value="<?=$username?>"
						placeholder="Gross- und Keinbuchstaben, min 6 Zeichen."
						pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}"
						title="Gross- und Keinbuchstaben, min 6 Zeichen."
						maxlength="30" 
						required="true">
				</div>
				<!-- password -->
				<div class="form-group">
					<label for="password">Password *</label>
					<input type="password" name="password" class="form-control" id="password"
						placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute"
						pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"
						title="mindestens einen Gross-, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen, mindestens 8 Zeichen lang,keine Umlaute."
						maxlength="255"
						required="true">
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