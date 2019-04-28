<?php 
    require_once("bootstrap/bootstrap.php");

    class Image {
        public static function getPostId(){ 
            $conn = Db::getConnection();
            $statement = $conn->prepare("select id from photo order by id desc limit 1");
            $statement->execute();
            $photo = $statement->fetch(PDO::FETCH_ASSOC);
            $post_id = $photo['id'] + 1;
            // Return current post id
            return $post_id;
        }

        public static function checkExtention($image){
            // Check extention of the image           
            $allowed =  array('png' ,'jpg', 'jpeg');
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            if(in_array($ext, $allowed)) {
                return true;
            }
            else {
                return false;
            }
        }

        public static function saveImageToDb($image, $croppedImage, $description) {
            $conn = Db::getConnection();
            $user_id = User::getUserId();
            $statement = $conn->prepare("insert into photo (`description`, `url`, `url_cropped`, `user_id`) VALUES (:description, :image, :croppedImage, :userId)");
            $statement->bindParam(":description", $description); 
            $statement->bindParam(":image", $image);
            $statement->bindParam(":croppedImage", $croppedImage);
            $statement->bindParam(":userId", $user_id);
            $result = $statement->execute();        
        }

        public static function saveImage($image, $imageSaveName){
            // Image file directory
            $target = "images/" . basename($image);
            move_uploaded_file($imageSaveName ,$target);
        }

        public static function saveCroppedImage($image) {
            // Image file directory
            $target = "images/" . basename($image);
            $info = getimagesize($target);
            $mime = $info['mime'];
                
            switch($mime) {
                case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    break;
                    
                case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    break;
            }
            // GET image                
            $im = $image_create_func($target);
            // CROP image
            $size = min(imagesx($im), imagesy($im));
            $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
            if ($im2 !== FALSE) {
                // SAVE cropped image
                $image_save_func($im2, "images/" . (Image::getPostId() -1)  . 'cropped-' .  $_FILES['image']['name']);
                imagedestroy($im2);
            }
        }

        public static function postHas3Reports($postId) {
            $conn = Db::getConnection();
            $statementCheck = $conn->prepare("select count(*) as count from inappropriate where post_id = :postId");
            $statementCheck->bindParam(":postId", $postId);
            $statementCheck->execute();
            $result = $statementCheck->fetch(PDO::FETCH_ASSOC);
    
            if ($result['count'] == 3) {
                return true;
            } else {
                return false;
            }
        }

    }