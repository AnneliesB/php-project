<?php
if (!empty($_POST)) {
    $config = parse_ini_file("config/config.ini");
    $conn = new PDO("mysql:host=localhost;dbname=".$config['db_name'], $config['db_user'], $config['db_password']);
    $email=$_POST['email'];
    $password=$_POST['password'];

    $statement = $conn->prepare("select * from user where email = :email");
    $statement->bindParam(":email", $email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if(password_verify($password, $user['password'])){
        header ("Location: index.php");
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
    <title>IMDSTAGRAM - login</title>
</head>
<body>

<div class="login">
    <div class="form formLogin">
        <form action="" method="post">
            <h2 class="formTitle">Sign In</h2>

            <?php if (isset($error)): ?>
                <div class="formError">
                    <p>
                        Sorry, we can't log you in with that email address and password. Can you try again?
                    </p>
                </div>
            <?php endif; ?>

            <div class="formField">
                <label for="email">Email</label>
                <input type="text" name="email">
            </div>
            <div class="formField">
                <label for="password">Password</label>
                <input type="password" name="password">
            </div>
            <div class="formField">
                <input type="submit" value="Sign In" class="btn btnPrimary">
                <input type="checkbox" id="rememberMe"><label for="rememberMe" class="labelInline">Remember me</label>
            </div>

            <div>
                <p>No account yet? <a href="register.php"> Sign up here</a></p>
            </div>
        </form>
    </div>
</div>

</body>
</html>