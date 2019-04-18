<?php 
    require_once("bootstrap/bootstrap.php");

    $conn = Db::getConnection();

    // GET username
    $sessionEmail = $_SESSION['email'];

    $statement = $conn->prepare("select * from user where email = :sessionEmail");
    $statement->bindParam(":sessionEmail", $sessionEmail);
    $statement->execute();
    $userProfile = $statement->fetch(PDO::FETCH_ASSOC);


    if(!empty($_POST)){    
        
        
        // UPLOAD image
        if(isset($_POST['upload'])) {
            // GET image name / filename
            $image = $_FILES['image']['name'];
            $croppedImage = "cropped-".$_FILES['image']['name'];

            // GET description
            $description = $_POST['description'];            

            // ! user_id
            $statement = $conn->prepare("insert into photo (`description`, `url`, `url_cropped`, `user_id`) VALUES (:description, :image, :croppedImage, :userId)");

            $statement->bindParam(":description", $description); 
            $statement->bindParam(":image", $image);
            $statement->bindParam(":croppedImage", $croppedImage);
            $statement->bindParam(":userId", $userProfile['id']);

            $statement->execute(); 
            
            // GET latest id
            $last_id = $conn->lastInsertId();   
            
            // image file directory
            $target = "images/" . $last_id . basename($image);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                
                $info = getimagesize($target);
                $mime = $info['mime'];

                
                switch($mime) {
                    case 'image/jpeg':
                        $image_create_func = 'imagecreatefromjpeg';
                        $image_save_func = 'imagejpeg';
                        //$new_image_ext = 'jpg';
                        break;
                    
                    case 'image/png':
                        $image_create_func = 'imagecreatefrompng';
                        $image_save_func = 'imagepng';
                        //$new_image_ext = 'png';
                        break;

                    default:
                        $error = "Unknown image tye";
                }


                // GET image                
                $im = $image_create_func($target);

                // CROP image
                $size = min(imagesx($im), imagesy($im));
                $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);

                if ($im2 !== FALSE) {
                    // SAVE cropped image
                    $image_save_func($im2, "images/".'cropped-'.$last_id.basename($image));
                    imagedestroy($im2);
                }

            }
                
            else{
                $error = "Failed to upload image";
            }

        }   
        
        
    }    

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>IMSTAGRAM - add post</title>
</head>

<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <?php if (isset($error)): ?>
            <div class="formError">
                <p>
                    <?php echo $error ?>
                </p>
            </div>
        <?php endif; ?>

        <h2 formTitle>Add post</h2>

        <div class="flexbox">
            <div class="formField">
                <label for="image">Picture</label>
                <input type="file" id="image" name="image">
            </div>
            <div class="formField">
                <label for="description">Description</label>
                
                <textarea id="description" name="description" rows="10" > </textarea>
            </div>
        </div>

        <div class="formField">
            <input type="submit" value="Post" name="upload" class="btn btnPrimary">
        </div>
    </form>
</body>

</html>