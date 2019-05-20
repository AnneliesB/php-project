<?php

require_once("bootstrap/bootstrap.php");

//Check if user session is active (Is user logged in?)
User::userLoggedIn();

# connect to load data
$sessionEmail = User::getSessionEmail();
$userProfile = User::findByEmail($sessionEmail);
if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    try {
        if (User::doChangeProfile($email, $password) == true) {
            # go back to profile page to view changes
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
<form action="" method="POST" enctype="multipart/form-data" class="editProfile">
    <h2 class="profileHeading">Edit profile</h2>
    <?php if (isset($error)): ?>
        <div class="formError">
            <p>
                <?php echo $error ?>
            </p>
        </div>
    <?php endif; ?>





        <div class="formField">
            <label for="image">Select image to upload:</label>
            <input type="file" id="image" name="image">
        </div>

        <div class="formField">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userProfile['username']); ?>">
        </div>

        <div class="formField">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($userProfile['email']); ?>">
        </div>

        <div class="formField">
            <label for="description">Description</label>
            <textarea rows="10" cols="30" id="description" name="description"
                      class="textarea"><?php echo htmlspecialchars($userProfile['description']); ?></textarea>
        </div>

        <div class="formField">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>



    <input type="submit" value="Update profile" name="upload" class="btn btnPrimary">


</form>

<script src="js/navigation.js"></script>
</body>
</html>
