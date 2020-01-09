<?php
session_start();

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

    <form action="login.php" method="post">
    Login:<br>
    <input type="text" name="login" ><br>
    Hasło:<br>
    <input type="password" name="pass" ><br><br>
    <input type="submit" value="Submit">
    </form>

    <?php
    echo $_SESSION['test'];
    echo $_SESSION['row'];
        if(isset($_SESSION['login_error'])) echo $_SESSION['login_error'];
        
    ?>
        
</body>
</html>