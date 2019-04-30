<?php
require_once("bootstrap/bootstrap.php");
require 'vendor/autoload.php';

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;


class Image
{
    public static function getPostId()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select id from photo order by id desc limit 1");
        $statement->execute();
        $photo = $statement->fetch(PDO::FETCH_ASSOC);
        $post_id = $photo['id'] + 1;

        // Return current post id
        return $post_id;
    }

    public static function checkExtention($image)
    {
        // Check extention of the image
        $allowed = array('png', 'jpg', 'jpeg');
        $ext = pathinfo($image, PATHINFO_EXTENSION);

        if (in_array($ext, $allowed)) {
            return true;
        } else {
            return false;
        }
    }

    public static function saveImageToDb($image, $croppedImage, $description)
    {
        $conn = Db::getConnection();
        $user_id = User::getUserId();

        $statement = $conn->prepare("insert into photo (`description`, `url`, `url_cropped`, `user_id`) VALUES (:description, :image, :croppedImage, :userId)");
        $statement->bindParam(":description", $description);
        $statement->bindParam(":image", $image);
        $statement->bindParam(":croppedImage", $croppedImage);
        $statement->bindParam(":userId", $user_id);
        $result = $statement->execute();
    }

    public static function saveImage($image, $imageSaveName)
    {
        // Image file directory
        $target = "images/" . basename($image);
        move_uploaded_file($imageSaveName, $target);
    }

    public static function saveCroppedImage($image)
    {
        // Image file directory
        $target = "images/" . basename($image);

        $info = getimagesize($target);
        $mime = $info['mime'];


        switch ($mime) {
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
            $image_save_func($im2, "images/" . (Image::getPostId() - 1) . 'cropped-' . $_FILES['image']['name']);
            imagedestroy($im2);
        }

    }

    public static function saveMainColors($imageName)
    {
        $imageBaseName = basename($imageName);
        $conn = Db::getConnection();
        $palette = Palette::fromFilename('images/' . $imageName);
        // an extractor is built from a palette
        $extractor = new ColorExtractor($palette);

        // it defines an extract method which return the most “representative” colors
        $colors = $extractor->extract(4);
        #print_r($colors);
        $color1 = Color::fromIntToHex($colors[0]);
        $color2 = Color::fromIntToHex($colors[1]);
        $color3 = Color::fromIntToHex($colors[2]);
        $color4 = Color::fromIntToHex($colors[3]);

        $statement = $conn->prepare("UPDATE photo set color1 = :color1, color2 = :color2, color3 = :color3, color4 = :color4 where url = :url");
        $statement->bindParam(":color1", $color1);
        $statement->bindParam(":color2", $color2);
        $statement->bindParam(":color3", $color3);
        $statement->bindParam(":color4", $color4);
        $statement->bindParam(":url", $imageBaseName);
        $statement->execute();
    }

    public static function showImagesWithTheSameColor($color){
        // $id = (int) $_GET['id']; in index aanroepen om hex code te vinden
        // htmlspecialchars om de hashtags te vinden
        $conn = Db::getConnection();
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.color1 like '%" . $color . "%' OR  photo.color2 like '%" . $color . "%' OR  photo.color3 like '%" . $color . "%' OR  photo.color4 like '%" . $color . "%' order by id desc LIMIT 2");
       // $statement = $conn->prepare("SELECT * from photo where color1 = :color OR color2 = :color OR color3 = :color OR color4 = :color order by id desc");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;

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


        public static function time_elapsed_string($datetime, $full = false) {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);
        
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
        
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
        
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }

        

    }
