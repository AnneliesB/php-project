<?php

require_once("bootstrap/bootstrap.php");

//Check if user session is active (Is user logged in?)
if (isset($_SESSION['email'])) {
    //User is logged in, no redirect needed!
} else {
    //User is not logged in, redirect to login.php!
    header("location: login.php");
}

if (!empty($_POST)) {

    $oldPassword = $_POST['password'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    try {
        if (User::canChangePassword($oldPassword, $newPassword, $confirmNewPassword) == true) {
            User::doChangePassword($newPassword);
            $error = "Your password has been successfully updated";
            header("location: profile.php");
        }
    } catch (Throwable $t) {
        $error = $t->getMessage();
    }


}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>IMSTAGRAM - edit profile</title>
</head>
<body>
<?php include_once("nav.incl.php"); ?>
<form action="" method="POST" class="editPassword">
    <h2 class="profileHeading">Edit password</h2>
    <?php if (isset($error)): ?>
        <div class="formError">
            <p>
                <?php echo $error ?>
            </p>
        </div>
    <?php endif; ?>





        <div class="formField">
            <label for="password">Enter old password</label>
            <input type="password" id="password" name="password">
        </div>

        <div class="formField">
            <label for="new¨Password">Enter new password</label>
            <input type="password" id="newPassword" name="newPassword">
        </div>

        <div class="formField">
            <label for="confirmNewPassword">Confirm password</label>
            <input type="password" id="confirmNewPassword" name="confirmNewPassword">
        </div>



    <input type="submit" value="Update Password" name="upload" class="btn btnPrimary">


</form>

<script src="js/navigation.js"></script>

</body>
</html>
