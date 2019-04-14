<?php
require_once("bootstrap/bootstrap.php");
require_once("classes/User.php");
if (isset($_SESSION['id'])){

  $user->getUserD


if (!empty($_POST['password','username'])){
$conn = Db::getConnection();

        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $newpassword = htmlspecialchars($_POST['newpassword']);
        $confirmnewpassword = htmlspecialchars($_POST['confirmnewpassword']);
        $result = mysql_query("SELECT password FROM user WHERE user_id='$username'"); 

        if(!$result){
        echo "The username you does not exist"; //checking if the username is correct
        }else if($password!= mysql_result($result, 0)){
        echo "You entered an incorrect password"; //old password isn't found
        }

        if($newpassword=$confirmnewpassword) //making sure the passwords match

        $sql=mysql_query("UPDATE user SET password='$newpassword' where user_id='$username'");

        if($sql){
        echo "Congrats, you have a new password";
        }else{
       echo "Passwords do not match";
       }
       //description upload 
      } else if (!empty($_POST['description'])) {

        $user->setDescription($_POST['description']);
        $newdescription = $_POST['description'];
        $user->setDescription($newdescription);
       //change old with the new description
        if ($user->update($id)) {
            $error = "Your description is updated";
        } else {
            $error = "Something went wrong, Try again later.";
        }
    } else {
        $newdescription = $description;
        //nothing changes
    }
      
  }

   // $user->setDescription($description);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="profilepic" id="profilepic">
    <input type="submit" value="Upload Image" name="submit">
</form>
<p>
<label for="description">Description: </label>
<textarea rows="10" cols="30" id="description" class="textarea"></textarea>
</p>

<h1>Change Password</h1>
   <form method="POST" action="password_change.php">
    <table>
    <tr>
   <td>Enter your UserName</td>
    <td><input type="username" size="30" name="username"></td>
    </tr>
    <tr>
    <td>Enter your existing password:</td>
    <td><input type="password" size="30" name="password"></td>
    </tr>
  <tr>
    <td>Enter your new password:</td>
    <td><input type="password" size="30" name="newpassword"></td>
    </tr>
    <tr>
   <td>Re-enter your new password:</td>
   <td><input type="password" size="30" name="confirmnewpassword"></td>
    </tr>
    </table>
    <p><input type="submit" value="Update Password">
    </form>
   <p><a href="index.php">Home</a>
   <p><a href="logout.php">Logout</a>
</body>
</html>