<?php
$htmloutput = '<div class="asset">
<img src="https://riskinfo.com.au/resource-centre/files/2014/05/test-img.jpg" alt="asset">
<h2>Titel</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua.</p>
<form action="" method="">
<input type="submit" value="Edit">
<input type="submit" value="Delete">
</form>
</div>';
for ($i=0; $i < 3; $i++) { 
    $htmloutput .= $htmloutput;
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
<form action="">
    <input class="createbutton" type="submit" value="+ Erstellen">
</form>
</div>
<div class="wrapper">
<?=$htmloutput?>
</div>
    
</body>
</html>