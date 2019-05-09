<?php
require_once("Db.php");
//require "vendor/autoload.php";
//require_once __DIR__ . 'vendor/autoload.php';

$pathConfig = parse_ini_file("path.ini");
$path = $_SERVER['DOCUMENT_ROOT']. $pathConfig['mypath'] . "/vendor/autoload.php";
// voor annelies waarbij het soms ni werkt dit pad --> $path .= "/php-project/vendor/autoload.php";
include_once($path);

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
        $allowed = array('png', 'jpg', 'jpeg', 'JPG', 'PNG', 'JPEG');
        $ext = pathinfo($image, PATHINFO_EXTENSION);

        if (in_array($ext, $allowed)) {
            return true;
        } else {
            return false;
        }
    }

    public static function saveImageToDb($image, $croppedImage, $description, $city, $lat, $lng, $filter)
    {
        $conn = Db::getConnection();
        $user_id = User::getUserId();

        $statement = $conn->prepare("insert into photo (`description`, `url`, `url_cropped`, `user_id`, `city`, `lat`, `lng`, `filter`) VALUES (:description, :image, :croppedImage, :userId, :city, :lat, :lng, :filter)");
        $statement->bindParam(":description", $description);
        $statement->bindParam(":image", $image);
        $statement->bindParam(":croppedImage", $croppedImage);
        $statement->bindParam(":userId", $user_id);
        $statement->bindParam(":city", $city);
        $statement->bindParam(":lat", $lat);
        $statement->bindParam(":lng", $lng);
        $statement->bindParam(":filter", $filter);
        $result = $statement->execute();
    }

    public static function resizeJPG($image, $imageSaveName){
        $image_resized = imagescale(imagecreatefromjpeg($imageSaveName), 720);
        imagejpeg($image_resized, "images/" . basename($image));
    }

    public static function resizePNG($image, $imageSaveName){
        $image_resized = imagescale(imagecreatefrompng($imageSaveName), 720);
        imagepng($image_resized, "images/" . basename($image));
    }


    public static function saveImage($image, $imageSaveName)
    {
        // Image file directory
        $target = "images/" . basename($image);

        // check current size of image
        $data = getimagesize($imageSaveName);
        $width = $data[0];
        //var_dump($width);
        //$height = $data[1];
        if($width >720){
            // resize image, check extention first
            if(pathinfo($image, PATHINFO_EXTENSION) == 'jpg'){
                self::resizeJPG($image, $imageSaveName);
            } elseif (pathinfo($image, PATHINFO_EXTENSION) == 'png'){
                self::resizePNG($image, $imageSaveName);
            }
        } else {
            // saves original file
            move_uploaded_file($imageSaveName, $target);
        }





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
        $color1 = ltrim(Color::fromIntToHex($colors[0]), '#');
        $color2 = ltrim(Color::fromIntToHex($colors[1]), '#');
        $color3 = ltrim(Color::fromIntToHex($colors[2]), '#');
        $color4 = ltrim(Color::fromIntToHex($colors[3]), '#');

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

    public static function searchPosts($query) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.description like '%" . $query . "%' and photo.inappropriate = 0 order by id desc LIMIT 15");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public static function getAllPosts($userId, $hashtags) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select photo.*, user.username, photo.id from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) or photo.description REGEXP :hashtags and photo.inappropriate = 0 order by id desc limit 15");
        $statement->bindParam(":user_id", $userId);
        $statement->bindParam(":hashtags", $hashtags);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public static function getPostsByTag($tag) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.description like '%" . '#' . $tag . "%' and photo.inappropriate = 0 order by id desc LIMIT 15");
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


    public static function timeAgo($datetime, $full = false) {
        date_default_timezone_set('Europe/Brussels');

        // get current time
        $now = new DateTime;

        // get posted from db
        $ago = new DateTime($datetime);

        // calculate difference between current time and time from db
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

        foreach ($string as $key => &$value) {
            if ($diff->$key) {
                $value = $diff->$key . ' ' . $value . ($diff->$key > 1 ? 's' : '');
            } 
            else 
            {
                unset($string[$key]);
            }
        }
        
            // if you want full notation of difference in time
            if(!$full) {
                $string = array_slice($string, 0, 1);
            } 

            // return difference
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }        

        //retrieve a single post by it's id
        public static function getCurrentPost($post_id){
            $conn = Db::getConnection();
            $statement = $conn->prepare("select * from photo where id = :id");
            $statement->bindParam(":id", $post_id);
            $statement->execute();
            $post = $statement->fetch(PDO::FETCH_ASSOC);
            return $post;
        }

        //get the username of the person who posted the this post
        public static function getPostUsername($post_id){
            $conn = Db::getConnection();

            //get the post's user_id
            $statement = $conn->prepare("select photo.user_id from photo where id = :id");
            $statement->bindParam(":id", $post_id);
            $statement->execute();
            $user_id = $statement->fetch(PDO::FETCH_ASSOC);
            $user_id = $user_id['user_id'];

            //retreive the username by that user_id
            $statement = $conn->prepare("select username from user where id = :id");
            $statement->bindParam(":id", $user_id);
            $statement->execute();
            $username = $statement->fetch(PDO::FETCH_ASSOC);
            $username = $username['username'];

            //return the username
            return $username;
        }

    }
