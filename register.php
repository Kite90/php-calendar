<?php
    session_start();

    //start the validation
    if(isset($_POST['email'])) {

        //Assume everything is ok
        $all_fine = true;

        //Validate nick
        $nick = $_POST['nick'];

        if((strlen($nick) < 3) || (strlen($nick) > 20)) {
            $all_fine = false;
            $_SESSION['error_nick'] = "Nick must have less than 20, but more than 3 chars";
        }

        if(ctype_alnum($nick) == false) {
            $all_fine = false;
            $_SESSION['error_nick'] = "Nick must contain only letters";

        }

        //Validate email
        $email = $_POST['email'];
        $email_san = filter_var($email, FILTER_SANITIZE_EMAIL);

        if((filter_var($email, FILTER_SANITIZE_EMAIL) == false) || ($email != $email_san)) {
            $all_fine = false;
            $_SESSION['error_email'] = "invalid email";

        }

        //Validate password
        $pass1 = $_POST['pass'];
        $pass2 = $_POST['pass2'];

        if((strlen($pass1)<8) || (strlen($pass1)>20)){
            $all_fine = false;
            $_SESSION['error_pass'] = "Password must be between 8 to 20 chars";
        }

        if($pass1 != $pass2) {
            $all_fine = false;
            $_SESSION['error_pass'] = "Passwords do not match";
        }

        //Validate checkbox
        $checkbox = $_POST['check'];
        if(!isset($checkbox)) {
            $all_fine = false;
            $_SESSION['error_check'] = "Checkbox was not clicked";
        }

        //Looks fine, lets declare the data
        $_SESSION['register_nick'] = $nick;
        $_SESSION['register_email'] = $email;
        $_SESSION['register_pass1'] = $pass1;
        $_SESSION['register_pass2'] = $pass2;

        require_once("connect.php");
        mysqli_report(MYSQLI_REPORT_STRICT);

        //Check if there are errors with connection and proceed if not
        try{
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            if($connection->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            } else {

                //Is e-mail present in the database?
                $email_result = $connection->query("SELECT id FROM uzytkownicy WHERE email='$email'");
                if(!$email_result) {
                    throw new Exception($connection->error);
                } 

                $how_many_emails = $email_result->num_rows;
                if($how_many_emails>0) {
                    
                }
                
            }



        } catch(Exception $e) {
            echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
        }
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


    <form method="post">
    Nick<br>
    <input type="text" name="nick" ><br>
    <?php
    if(isset($_SESSION['error_nick'])) {
        echo $_SESSION['error_nick'].'<br';
        unset($_SESSION['error_nick']);
    }
    ?>

    <br>Email<br>
    <input type="text" name="email" ><br>
    <?php
    if(isset($_SESSION['error_email'])) {
        echo $_SESSION['error_email'].'<br';
        unset($_SESSION['error_email']);
    }
    ?>

    Hasło:<br>
    <input type="password" name="pass" ><br><br>
    <?php
    if(isset($_SESSION['error_pass'])) {
        echo $_SESSION['error_pass'].'<br';
        unset($_SESSION['error_pass']);
    }
    ?>

    Hasło2:<br>
    <input type="password" name="pass2" ><br><br>
    <br>


    <input type="checkbox" name="check" ><br>
    <?php
    if(isset($_SESSION['error_check'])) {
        echo $_SESSION['error_check'].'<br';
        unset($_SESSION['error_check']);
    }
    ?>

    <br><br>
    <input type="submit" value="Submit">
    </form>

   
        
</body>
</html>