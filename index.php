<?php
$htmloutput = '<div class="asset">
<img src="https://riskinfo.com.au/resource-centre/files/2014/05/test-img.jpg" alt="asset">
<h2>Titel</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua.</p>
</div>';
for ($i=0; $i < 4; $i++) { 
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
        <title>Home</title>
    </head>

    <body>

        <ul>
            <li><a class="active" href="index.php">Home</a></li>
            <li><a href="mygallery.php">MyGallery</a></li>
            <li><a href="account.php">Konto</a></li>
            <li class="navfloat_right"><a href="register.php">Register</a></li>
            <li class="navfloat_right"><a href="login.php">Login</a></li>
            <li class="navfloat_right"><a href="logout.php">Logout</a></li>
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