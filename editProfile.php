<?php

require_once("bootstrap/bootstrap.php");

# connect to load data
$conn = Db::getConnection();
$sessionEmail = $_SESSION['email'];

$statement = $conn->prepare("SELECT * from user where email = :sessionEmail");
$statement->bindParam(":sessionEmail", $sessionEmail);
$statement->execute();
$userProfile = $statement->fetch(PDO::FETCH_ASSOC);


if (!empty($_POST)) {

    $email = htmlspecialchars($_POST['email']);

    # check if email has changed
    if ($email != $userProfile['email']) {

        # check if new email is available and is a valid email address
        if (User::isEmailAvailable($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

            # echo !empty($_FILES['image']['size'] == 0) ? 'true' : 'false';
            # https://stackoverflow.com/questions/14458553/check-if-specific-input-file-is-empty/14458594#14458594
            # check if a new image has been uploaded - size has to be bigger than 0
            if ($_FILES['image']['size'] != 0) {

                # save new Email + image + description

                $image = $_FILES['image']['name'];
                $description = htmlspecialchars($_POST['description']);

                # update the database
                $updateStatement = $conn->prepare("UPDATE user set description=:newDescription, image=:image, email=:newEmail where email=:sessionEmail");
                $updateStatement->bindParam(":newDescription", $description);
                $updateStatement->bindParam(":image", $image);
                $updateStatement->bindParam(":newEmail", $email);
                $updateStatement->bindParam(":sessionEmail", $sessionEmail);
                $updateStatement->execute();

                # change session email value
                $_SESSION['email'] = $email;

                // image file directory
                $target = "images/profilePictures/" . $userProfile['id'] . basename($image);

                // move file
                move_uploaded_file($_FILES['image']['tmp_name'], $target);

                # go back to profile page to view changes
                header("location: profile.php");

            } else {
                # no new image
                # save new email + description

                $description = htmlspecialchars($_POST['description']);
                $email = htmlspecialchars($_POST['email']);

                $updateStatement = $conn->prepare("UPDATE user set description= :newDescription, email = :newEmail where email = :sessionEmail");
                $updateStatement->bindParam(":newEmail", $email);
                $updateStatement->bindParam(":newDescription", $description);
                $updateStatement->bindParam(":sessionEmail", $sessionEmail);
                $updateStatement->execute();

                # change session email value
                $_SESSION['email'] = $email;

                # go back to profile page to view changes
                header("location: profile.php");


            }

        } else {
            if (User::isEmailAvailable($email) == false) {
                $error = "This email is not available";
            } else {
                $error = "Please use a valid email address";
            }
        }

    } else {
        # no new email address
        # check if a new image has been uploaded

        if ($_FILES['image']['size'] != 0) {
            # save new image
            # save description - no need to check, description can be empty

            $image = $_FILES['image']['name'];
            $description = htmlspecialchars($_POST['description']);
            $updateStatement = $conn->prepare("UPDATE user set description=:newDescription, image=:image where email=:sessionEmail");
            $updateStatement->bindParam(":newDescription", $description);
            $updateStatement->bindParam(":sessionEmail", $sessionEmail);
            $updateStatement->bindParam(":image", $image);
            $updateStatement->execute();

            # sla images lokaal op
            // image file directory
            $target = "images/profilePictures/" . $userProfile['id'] . basename($image);

            // move file
            move_uploaded_file($_FILES['image']['tmp_name'], $target);

            # go back to profile page to view changes
            header("location: profile.php");

        } else {
            # no new image
            #save description

            $description = htmlspecialchars($_POST['description']);
            $updateStatement = $conn->prepare("UPDATE user set description= :newDescription where email = :sessionEmail");
            $updateStatement->bindParam(":newDescription", $description);
            $updateStatement->bindParam(":sessionEmail", $sessionEmail);
            $updateStatement->execute();

            # go back to profile page to view changes
            header("location: profile.php");
        }

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
<form action="" method="POST" enctype="multipart/form-data" class="editProfile">
    <?php if (isset($error)): ?>
        <div class="formError">
            <p>
                <?php echo $error ?>
            </p>
        </div>
    <?php endif; ?>

    <fieldset>

        <legend>Personal information</legend>

        <div class="formField">
            <label for="image">Select image to upload:</label>
            <input type="file" id="image" name="image">
        </div>

        <h3><?php echo $userProfile['username']; ?></h3>

        <div class="formField">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?php echo $userProfile['email']; ?>">
        </div>

        <div class="formField">
            <label for="description">Description</label>
            <textarea rows="10" cols="30" id="description" name="description"
                      class="textarea"><?php echo $userProfile['description']; ?></textarea>
        </div>


    </fieldset>

    <input type="submit" value="Update profile" name="upload" class="btn btnPrimary">


</form>
</body>
</html>
