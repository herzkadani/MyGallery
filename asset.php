<?php
if(!empty($_POST)){
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
}

$error = "";
$pagetitle='Asset';
$title='';
$description='';
$sqlaction='';
$assetid='';

// check if user is logged in
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    $error="Sie müssen sich zuerst auf der <a href=\"login.php\">Login-Seite</a> anmelden um auf diese Seite zugreifen zu können.";
    header("Location: login.php");
    die();
}

// check if user wants to create, edit or delete an asset
if($_SERVER['REQUEST_METHOD'] == "POST"){
    include 'dbconnector.inc.php';

    // check if user wants to create an asset
    if(isset($_POST['create'])){

        $sqlaction = 'insert';
        $pagetitle = 'Asset erstellen';

    }else if(isset($_POST['edit'])&&$_POST['edit']){

        // create html page for editing an asset
        //get asset from database
        $assetid = $_POST['asset_id'];
        $sql = "SELECT * FROM asset WHERE id = $assetid";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();

        // check if asset belongs to user
        if($row['fk_user_id'] == $_SESSION['user_id']){
            $pagetitle = htmlspecialchars($row['title']).' bearbeiten';
            $title = htmlspecialchars($row['title']);
            $description = htmlspecialchars($row['description']);
            $sqlaction = 'update';

        }else{
            $error = "Sie haben keine Berechtigung auf dieses Bild zuzugreifen.";
            header("Location: mygallery.php");
            die();
        }

    }else if(isset($_POST['delete'])&&$_POST['delete']){

        // delete asset from database
        $assetid = $_POST['asset_id'];
        $query = "SELECT * FROM asset WHERE id = ? AND fk_user_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ii", $assetid, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if($row['fk_user_id'] == $_SESSION['user_id']){
            $sql = "DELETE FROM asset WHERE id = $assetid";
            $result = $mysqli->query($sql);
            $message = "Bild erfolgreich gelöscht.";
            header("Location: mygallery.php");
            die();

            
        }else{
            $error = "Sie haben keine Berechtigung auf dieses Bild zuzugreifen.";
        }
    
        
    }else if(isset($_POST['insert'])&&$_POST['insert']){

        // insert asset into database
        if (!(isset($_POST["title"]) && !empty(trim($_POST["title"])) && strlen(trim($_POST["title"]))<=100)) $error .= "Geben Sie bitte einen korrekten Titel ein. <br>";
        if (!(isset($_POST["description"]) && !empty(trim($_POST["description"])) && strlen(trim($_POST["description"]))<=100)) $error .= "Geben Sie bitte eine korrekte Beschreibung ein. <br>";

        $title = trim($_POST['title']);
        $description = trim($_POST['description']);

        if(empty($error)){
            $query = "INSERT into asset (title, description, fk_user_id) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ssi", $title, $description, $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $message .= "Bild erfolgreich erstellt.";
            header("Location: mygallery.php");
            die();
        
        }else{
            $pagetitle = 'Asset erstellen';
            $sqlaction = 'insert';
        }
    

    }else if(isset($_POST['update'])&&$_POST['update']){

       // update asset into database
       if (!(isset($_POST["title"]) && !empty(trim($_POST["title"])) && strlen(trim($_POST["title"]))<=100)) $error .= "Geben Sie bitte einen korrekten Titel ein. <br>";
       if (!(isset($_POST["description"]) && !empty(trim($_POST["description"])) && strlen(trim($_POST["description"]))<=100)) $error .= "Geben Sie bitte eine korrekte Beschreibung ein. <br>";

        $title = trim($_POST['title']);
        $description = trim($_POST['description']);

        if(!$error){
            $query = "Update asset SET title = ?, description = ? WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ssi", $title, $description, $_POST['asset_id']);
            $stmt->execute();
            header("Location: mygallery.php");
            die();
        }else{
            $pagetitle = 'Asset bearbeiten';
            $sqlaction = 'update';
            $assetid = $_POST['asset_id'];
        }

    }else if(isset($_POST['cancel'])&&$_POST['cancel']){
        // cancel action
        header("Location: mygallery.php");
        die();
    }
}else{
    $error='URL not valid';
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$pagetitle?></title>
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
	
		<div class="container">
			<h1><?=$pagetitle?></h1>
			
			<?php
				// fehlermeldung oder nachricht ausgeben
				if(!empty($message)){
					echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
				} else if(!empty($error)){
					echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
				}
			?>
           
			<form action="asset.php" method="POST">
				<div class="form-group">
					<label for="title">Titel</label>
					<input type="text" name="title" class="form-control" id="title" placeholder="Titel" value="<?=$title?>" maxlength="100" required>
				</div>
				<div class="form-group">
					<label for="description">Beschreibung</label>
					<input type="text" name="description" class="form-control" id="description" placeholder="Beschreibung" value="<?=$description?>" maxlength="100" required>
				</div>
                <input type="hidden" name="asset_id" value="<?=$assetid?>">
		  		<button type="submit" name="<?=$sqlaction?>" value="<?=$sqlaction?>" class="btn btn-info">Speichern</button>
		  		<button type="submit" name="cancel" value="cancel" class="btn btn-warning" formnovalidate>Abbrechen</button>
			</form>
		</div>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	</body>
</html>