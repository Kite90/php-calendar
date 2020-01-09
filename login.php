<?php

session_start();

//If login and email were not created
if ((!isset($_POST['login'])) || (!isset($_POST['pass'])))
{
    header('Location: index.php');
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
        $passw = $_POST['pass'];

        // $login = htmlentities($login, ENT_QUOTES, "UTF-8");

        if ($result = $connection->query(
			sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",
			mysqli_real_escape_string($connection,$login)))) {

                $how_many_users = $result->num_rows;

                //If we found a user
                if($how_many_users>0) {
                    $row = $result->fetch_assoc();
                    // $_SESSION['row'] = $row['login'];

                    $_SESSION['test'] = "nie udalo sie";

                    //Password correct
                    if(password_verify($passw, $row['pass'])) {
                        $_SESSION['test'] = "udalo sie";
                        $_SESSION['logged'] = true;

                      
                        unset($_SESSION['login_error']);
                        
                        $result->free_result();
                        header('Location: panel.php');
                    }

                    //Pass incorrect
                    else {
                        $_SESSION['login_error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                        header('Location: index.php');
                    }


                } 
                //If user not found
                else {
                    $_SESSION['login_error'] = '<span style="color:red">Uzytkownik nieznaleziony!</span>';
                    header('Location: index.php');

                }

        }
        else
			{
				throw new Exception($polaczenie->error);
            }
            
            $connection->close();

    }

} catch(Exception $e) {
    echo "There is an issue with the server";
    echo $e;
}









?>