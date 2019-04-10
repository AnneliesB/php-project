<?php
    require_once("bootstrap/bootstrap.php");

    if( !empty($_POST) ){

        //Check if register fields are not empty strings
        if( !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['passwordConfirm']) ){

            $firstname = htmlspecialchars($_POST['firstname']);
            $lastname = htmlspecialchars($_POST['lastname']);
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $passwordConfirm = htmlspecialchars($_POST['passwordConfirm']);

            //Check if firstname is not longer than 30chars
            if( User::checkLength($firstname, 30)){
                $error = "Firstname cannot be longer than 30 characters.";
            }

            //Check if lastname is not longer than 30chars
            if( User::checkLength($lastname, 30)){
                $error = "Lastname cannot be longer than 30 characters.";
            }

            //Check if username is not longer than 30chars
            if( User::checkLength($username, 30)){
                $error = "Username cannot be longer than 30 characters.";
            }

            //Check if email is legit
            if( filter_var($email, FILTER_VALIDATE_EMAIL) ) {
                //Email has valid input
                //Do nothing, advance to next code lines
            }else {
                //email input is not valid, show error to user to check on this field
                $error = "Please use a valid email address!";
            }

            //Check if email is not in our DB yet
            if( User::isEmailAvailable($email) ){
                //Email is available, do nothing
            }else{
                //Email is not available, show error to user
                $error = "A user with this email address is already registered.";
            }

            //Check if username is not in our DB yet
            if( User::isUsernameAvailable($username) ){
                //Username is available, do nothing
            }else{
                //Username is not available, show error to user
                $error = "This username is already registered.";
            }

            //Do passwords equal?
            if( $password !== $passwordConfirm){
                //Passwords not equal, show error to user
                $error = "Password fields are not equal, please enter them again";
            }

            //Check if no error is set before attempting to create a new user
            if( !isset($error) ){

                //Start new user obj, set properties and call the register function
                $user = new User();

                $user->setFirstname($firstname);
                $user->setLastname($lastname);
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setPassword($password);

                if( $user->register() ){
                    //Start new session for registered user
                    session_start();
                    $_SESSION['email'] = $user->getEmail();

                    //Redirect to index
                    header("location: index.php");
                }else{
                    //Registration method failed, show error basic error to user (Real error is logged in register method)
                    $error = "User could not be registered, there has been a sudden error.";
                }

            }


        }else{
            //Some fields are empty, show error to user
            $error = "All fields are required! Please check again.";
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
    <div class="form formLogin">
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