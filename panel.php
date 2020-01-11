<?php
session_start();

//if user is logged in
if((!isset($_SESSION['logged'])) || ($_SESSION['logged']!=true)) {
    header('Location: panel.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Korepetycje Ady</title>
</head>
<body>

   <?php
    echo "<p>Witaj ".$_SESSION['user'].'! <a href="logout.php">Wyloguj się</a></p>';
    echo "<p>Posiadasz ".$_SESSION['funds'].' funduszy</p>';


    ?>
        
</body>
</html>