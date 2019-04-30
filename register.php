<?php
    require_once("bootstrap/bootstrap.php");

    if( !empty($_POST) ){

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['passwordConfirm'];

            //Try to start new user obj, set properties and call the register function
            try{
                $user = new User();

                $user->setFirstname($firstname);
                $user->setLastname($lastname);
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setPassword($password);
                $user->setPasswordConfirmation($passwordConfirm);

                if( $user->register() ){
                    $user->login();
                }

            }catch( Exception $e){
                $error = $e->getMessage();
            }
    }
    

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>IMDSTAGRAM - Signup</title>
</head>
<body>

<div class="login">
    <div class="form formLogin formRegister">
        <form action="" method="post">
            <h2 class="formTitle">Signup</h2>

            <?php if (isset($error)): ?>
                <div class="formError">
                    <p>
                        <?php echo $error ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="formInput">
                <div class="formField">
                    <label for="firstname">Firstname</label>
                    <input type="text" name="firstname">
                </div>
                <div class="formField">
                    <label for="lastname">Lastname</label>
                    <input type="text" name="lastname">
                </div>
                <div class="formField">
                    <label for="username">Username</label>
                    <p id="usernameFeedback" class="ajaxFeedback hidden"></p>
                    <input type="text" id="username" name="username">
                </div>
                <div class="formField">
                    <label for="email">Email</label>
                    <p id="emailFeedback" class="ajaxFeedback hidden"></p>
                    <input type="text" id="email" name="email">
                </div>
                <div class="formField">
                    <label for="password">Password</label>
                    <input type="password" name="password">
                </div>
                <div class="formField">
                    <label for="passwordConfirm">Password Confirmation</label>
                    <input type="password" name="passwordConfirm">
                </div>

                <div class="formField">
                    <input type="submit" value="Sign up" class="btn btnPrimary">
                </div>
            </div>

            <div class="redirectLink">
                <p>Already have an account? <a href="login.php"> Log in here</a></p>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="js/register.js"></script>

</body>
</html>