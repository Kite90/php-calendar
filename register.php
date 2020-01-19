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

        //Pass hash
        $pass_hash = password_hash($pass1, PASSWORD_DEFAULT);

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
                    $all_fine = false;
                    $_SESSION['error_email'] = "Email is present in the database";
                    
                } 

                //Is nick present in the database?
                $nick_result = $connection->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
                if(!$nick_result) {
                    throw new Exception($connection->error);
                }
                $how_many_users = $nick_result->num_rows;
                if($how_many_users>0) {
                    $all_fine = false;
                    $_SESSION['error_nick'] = "Nick is taken!";
                }

                if($all_fine==true) {
                    if($connection->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$pass_hash', '$email', 100, 100, 100, 14, 100)")) {
                        $_SESSION['register_succeed'] = true;
                        header('Location: welcome.php');
                    } else {
                        throw new Exception($connection->error);
                    }
                }

                $connection->close();
                
            }



        } catch(Exception $e) {
            echo '<span style="color:red;">Server error!</span>';
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
    <?php include './inc/header.php';?>
</head>
<body class="text-center">



    <form class="form-custom" method="post">
        <h1>Register</h1>

        <label for="nick" class="sr-only">Nick</label>
        <input type="text" id="nick" name="nick" class="form-control" placeholder="Nick" required autofocus>
    
       
        <?php
        if(isset($_SESSION['error_nick'])) {
            echo $_SESSION['error_nick'].'<br>';
            unset($_SESSION['error_nick']);
        }
        ?>
        

        <label for="email" class="sr-only">Email</label>
        <input type="text" name="email" id="email" class="form-control" placeholder="Email" required>
        <?php
        if(isset($_SESSION['error_email'])) {
            echo $_SESSION['error_email'].'<br>';
            unset($_SESSION['error_email']);
        }
        ?>


        <label for="pass" class="sr-only">Password</label>
        <?php if(isset($_SESSION['error_pass'])) {
           echo '<input type="password" name="pass" id="pass" class="form-control is-invalid" placeholder="Password" required>';
        } else {
           echo '<input type="password" name="pass" id="pass" class="form-control" placeholder="Password" required>';
        }
        ?>
        
        <?php
        if(isset($_SESSION['error_pass'])) {
            echo $_SESSION['error_pass'].'<br>';
            unset($_SESSION['error_pass']);
        }
        ?>

        
        <label for="pass2" class="sr-only">Password</label>
        <input type="password" name="pass2" id="pass2" class="form-control" placeholder="Password" required>
        <br>

        <!-- <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div> -->

      <div class="checkbox mb-3">
         <label>
        <input type="checkbox" name="check" >Checkbox
        </label>
        <?php
        if(isset($_SESSION['error_check'])) {
            echo $_SESSION['error_check'].'<br';
            unset($_SESSION['error_check']);
        }
        ?>
        
       </div>

        <input type="submit" class="btn btn-primary" value="Submit">
    </form>

   
<?php include './inc/footer.php';?> 
</body>
</html>