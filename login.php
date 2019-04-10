<?php
require_once("bootstrap/bootstrap.php");
if (!empty($_POST)) {
    $conn = Db::getConnection();
    /**
     * htmlspecialchars prevents the abuse of html tags in the input fields
     * the tags included will be transformed into text instead and will be part of the input
     */
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $statement = $conn->prepare("select * from user where email = :email");
    $statement->bindParam(":email", $email); # the email parameter is bound to :email to prevent sql-injection
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['imdstagram'] = true;
        header("location: index.php");
    } else {
        $error = true;
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
    <title>IMDSTAGRAM - login</title>
</head>
<body>

<div class="login">
    <div class="form formLogin">
        <form action="" method="post">
            <h2 class="formTitle">Login</h2>

            <?php if (isset($error)): ?>
                <div class="formError">
                    <p>
                        Sorry, we can't log you in with that email address and password. Can you try again?
                    </p>
                </div>
            <?php endif; ?>
            <div class="formInput">
                <div class="formField">
                    <label for="email">Email</label>
                    <input type="text" name="email">
                </div>
                <div class="formField">
                    <label for="password">Password</label>
                    <input type="password" name="password">
                </div>

                <div class="formField">
                    <input type="submit" value="login" class="btn btnPrimary">
                    <!--                <input type="checkbox" id="rememberMe"><label for="rememberMe" class="labelInline">Remember me</label>-->
                </div>
            </div>

            <div class="redirectLink">
                <p>No account yet? <a href="register.php"> Sign up here</a></p>
            </div>
        </form>
    </div>
</div>

</body>
</html>