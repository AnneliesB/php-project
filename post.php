<?php 
    if(!empty($_POST)){
        $config = parse_ini_file("config/config.ini");
        $conn = new PDO("mysql:host=localhost;dbname=" . $config['db_name'], $config['db_user'], $config['db_password']);

        // Initialize message variable
        //$msg = "";
  
        // UPLOAD image
        if(isset($_POST['upload'])) {
            // GET image name / filename
            $image = $_FILES['image']['name'];
            $croppedImage = "cropped-".$_FILES['image']['name'];

            // GET description
            $description = $_POST['description'];            

            $statement = $conn->prepare("insert into photo (`description`, `url`, `urlCrop`) VALUES (:description, :image, :croppedImage)");

            $statement->bindParam(":description", $description); 
            $statement->bindParam(":image", $image);
            $statement->bindParam(":croppedImage", $croppedImage);

            $statement->execute(); 
            
            // GET latest id
            $last_id = $conn->lastInsertId();   
            
            // image file directory
            $target = "images/".$last_id.basename($image);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                //$msg = "Image uploaded successfully";


                $info = getimagesize($target);
                $mime = $info['mime'];

                var_dump($mime);

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
                        throw new Exception('Unknown image type.');                    
                }


                // GET image                
                $im = $image_create_func($target);

                //CROP image
                $size = min(imagesx($im), imagesy($im));
                $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);

                if ($im2 !== FALSE) {
                    $image_save_func($im2, "images/".'cropped-'.$last_id.basename($image));
                    imagedestroy($im2);
                }

            }
                
            else{
                //$msg = "Failed to upload image";
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
    <title>Add post</title>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <h2 formTitle>Post picture</h2>
        
        <div class="formField">
            <label for="image">Picture</label>
			<input type="file" id="image" name="image">
        </div>
		<div class="formField">
			<label for="description">Description</label>
			<input type="text" id="description" name="description">
		</div>

		<div class="formField">
			<input type="submit" value="Post" name="upload" class="btn">	
		</div>
    </form>   
</body>
</html>