<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>mon profil</h1>

    <?php
    if(isset($_SESSION["user"])) {
        $infoSession = $_SESSION["user"];
    }?>

        
    <p>pseudo: <?= $infoSession["pseudo"] ?></p>
    <p>email: <?= $infoSession["email"] ?></p>

</body>
</html>