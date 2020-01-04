<?php

//If login and email were not created
if ((!isset($_POST['login'])) || (!isset($_POST['pass'])))
{
    header('Location: ./index.php');
    exit();
} 

require_once("connect.php");
try {
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if($connection->connect_errno!=0){
        throw new Exception(mysqli_connect_errno());
    } 
    //Connection is OK and we can login a user
    else {

        $login = $_POST['login'];
        $pass = $_POST['pass'];

        $login = htmlentities($login, ENT_QUOTES, "UTF-8");

        if ($result = $connection->query(
			sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",
			mysqli_real_escape_string($connestion,$login)))) {



        }

    }

} catch(Exception $e) {
    echo "There is an issue with the server";
    echo $e;
}









?>