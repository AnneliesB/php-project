<?php
require_once("bootstrap/bootstrap.php");
require_once("classes/User.php");

  if (!empty($_POST['image'])) {
    $conn = Db::getConnection();
    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        if ($_FILES['file']['error'] > 0) {
            switch ($_FILES['file']['error']) {
                case 1:
                    $error = "U mag maximaal 32MB opladen.";
                    break;
                default:
                    $error = "Sorry, uw upload kon niet worden verwerkt.";
            }
        } else {
            $allowedtypes = array("image/jpg", "image/jpeg", "image/png", "image/gif"); //soorten ondersteunde profile pics
            $filename = $_FILES['file']['tmp_name'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $fileinfo = $finfo->file($filename);
            if (in_array($fileinfo, $allowedtypes)) {
              
                if($_FILES['file']['type'] == "image/jpg"){
                    $newfilename = "uploads/image/" . $id . "_" . time() . ".jpg";
                } else if($_FILES['file']['type'] == "image/jpeg"){
                    $newfilename = "uploads/image/" . $id . "_" . time() . ".jpeg";
                } else if($_FILES['file']['type'] == "image/png") {
                    $newfilename = "uploads/image/" . $id . "_" . time() . ".png";
                } else if($_FILES['file']['type'] == "image/gif"){
                    $newfilename = "uploads/image/" . $id . "_" . time() . ".gif";
                }
                //toevoegen van image aan de databank met time stamp en de ID van de user
              //om te weten van wie welke profile pic is.
                if (move_uploaded_file($_FILES['file']['tmp_name'], $newfilename)) {
                    $error = "Upload gelukt.";
                    $user -> setImage($newfilename);
                    if($user->update($id)){
                        $error = "Je profiel is aangepast.";
                    } else {
                        $error = "Sorry, de upload is mislukt.";
                    }
                } else {
                    $error = "Sorry, de upload is mislukt.";
                }
            } else {
                $error = "Sorry, enkel afbeeldingen zijn toegestaan.";
            }
        }
    }
}

//***************************************************************************************************************************************** */
else if (!empty($_POST['password'])){
$conn = Db::getConnection();

        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $newpassword = htmlspecialchars($_POST['newpassword']);
        $confirmnewpassword = htmlspecialchars($_POST['confirmnewpassword']);
        $result = mysql_query("SELECT password FROM user WHERE user_id='$username'"); 

        if(!$result){
        echo "The username you does not exist"; //check of je de correcte username hebt
        }else if($password!= mysql_result($result, 0)){
        echo "You entered an incorrect password"; //oud password nietgevonden
        }else if( User::minLength($newpassword, 8)){
          $error = "Password must be minimum 8 chars long.";
        } else if($newpassword=$confirmnewpassword) //checkt passwoorden

        $sql=mysql_query("UPDATE user SET password='$newpassword' where user_id='$username'");

        if($sql){
        echo "Congrats, you have a new password";
        }else{
       echo "Passwords do not match";
       }
       //**************************************************************************************************************************************** */
       //description upload 
      } else if (!empty($_POST['description'])) {
        $conn = Db::getConnection();

        $user->setDescription($_POST['description']);
        $newdescription = $_POST['description'];
        $user->setDescription($newdescription);
       //verander oude met nieuwe desc
        if ($user->update($id)) {
            $error = "Your description is updated";
        } else {
            $error = "Something went wrong, Try again later.";
        }
    } else {
        $newdescription = $description;
        //nothing changes
    }
      
  
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
<?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="profilepic" id="profilepic">
<p>
<br>
<label for="description">Description: </label>
<textarea rows="10" cols="30" id="description" name="description" class="textarea"></textarea>
</p>

<h1>Change Password</h1>
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
    <p><input type="submit" value="Update profile">
    </form>
   <p><a href="index.php">Home</a>
   <p><a href="logout.php">Logout</a>
</body>
</html>