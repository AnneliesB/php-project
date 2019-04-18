<?php

  require_once("bootstrap/bootstrap.php");

  //Check if user session is active (Is user logged in?)
    if( isset($_SESSION['email']) ){
        //User is logged in, no redirect needed!
    }else{
        //User is not logged in, redirect to login.php!
    header("location: login.php");
    }


  if(!empty($_POST)) {
    $conn = Db::getConnection();

    # get current user's info
    $sessionEmail = $_SESSION['email'];
    $statement = $conn->prepare("SELECT * from user where email = :sessionEmail");
    $statement->bindParam(':sessionEmail', $sessionEmail);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);



    $oldPassword = htmlspecialchars($_POST['password']);
    $newPassword = htmlspecialchars($_POST['newPassword']);
    $confirmNewPassword = htmlspecialchars($_POST['confirmNewPassword']);

    // CHECK password to change data
    # compare current password from input to database password
    if (password_verify($oldPassword, $user['password'])) {

        # check if newPassword is filled in // change to !empty?
        if (isset($newPassword)) {

            # check if newPassword is strong enough and is the same as confirmNewPassword
            if ((strlen($newPassword) >= 8) && $newPassword == $confirmNewPassword) {

              $hashNewPassword =  Security::hash($newPassword);

              // UPDATE new data
              $updateStatement = $conn->prepare("update user set password= :newPassword where email = :email");
              $updateStatement->bindParam(":email", $sessionEmail);
              $updateStatement->bindParam(":newPassword", $hashNewPassword);
              $updateStatement->execute();


              $error = "ok";

            }

            else {
                # check why the newPassword is not accepted
                if ((strlen($newPassword) >= 8) == false) {
                    $error = "New password is not strong/long enough";
                }

                else {
                    $error = "New password does not match the confirmation password";
                }
            }

        }

        else {
            # we won't update the password
            # check if email is not empty
            # check if image is correct
        }
    }

    else {
        $error = "Wrong password";
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
      <title>IMSTAGRAM - edit profile</title>
  </head>
  <body>
  <form action="" method="POST" class="editPassword">
      <?php if (isset($error)): ?>
          <div class="formError">
              <p>
                  <?php echo $error ?>
              </p>
          </div>
      <?php endif; ?>

      <fieldset>

          <legend>Change password</legend>

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

      </fieldset>

      <input type="submit" value="Update Password" name="upload" class="btn btnPrimary">


  </form>
  </body>
  </html>
