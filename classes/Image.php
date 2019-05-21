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

    public static function saveImageToDb($image, $croppedImage, $description, $city, $lat, $lng, $filter, $category)
    {
        $conn = Db::getConnection();
        $user_id = User::getUserId();

        $statement = $conn->prepare("insert into photo (`description`, `url`, `url_cropped`, `user_id`, `city`, `lat`, `lng`, `filter`, `category_id`) VALUES (:description, :image, :croppedImage, :userId, :city, :lat, :lng, :filter, :category)");
        $statement->bindParam(":description", $description);
        $statement->bindParam(":image", $image);
        $statement->bindParam(":croppedImage", $croppedImage);
        $statement->bindParam(":userId", $user_id);
        $statement->bindParam(":city", $city);
        $statement->bindParam(":lat", $lat);
        $statement->bindParam(":lng", $lng);
        $statement->bindParam(":filter", $filter);
        $statement->bindParam(":category", $category);
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
        // Orientate the photo
        $exif = exif_read_data($imageSaveName);
        $exif['Orientation'];

        

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
        # check if we have 4 different colors

        #if first two colors are equal, all the other colors will be equal too => 1 color

        if(count($colors) == 1){
            $color1 = ltrim(Color::fromIntToHex($colors[0]), '#');
            $color2 = ltrim(Color::fromIntToHex($colors[0]), '#');
            $color3 = ltrim(Color::fromIntToHex($colors[0]), '#');
            $color4 = ltrim(Color::fromIntToHex($colors[0]), '#');
        } else if (count($colors) == 2){
            # if color 2 and 3 are equal, color 4 will be the same color => we have 2 colors
            $color1 = ltrim(Color::fromIntToHex($colors[0]), '#');
            $color2 = ltrim(Color::fromIntToHex($colors[1]), '#');
            $color3 = ltrim(Color::fromIntToHex($colors[0]), '#');
            $color4 = ltrim(Color::fromIntToHex($colors[1]), '#');
        } else if (count($colors) == 3){
            # if color 3 and 4 are equal => we have 3 main colors
            $color1 = ltrim(Color::fromIntToHex($colors[0]), '#');
            $color2 = ltrim(Color::fromIntToHex($colors[1]), '#');
            $color3 = ltrim(Color::fromIntToHex($colors[2]), '#');
            $color4 = ltrim(Color::fromIntToHex($colors[0]), '#');
        } else {
            # all colors are different
            $color1 = ltrim(Color::fromIntToHex($colors[0]), '#');
            $color2 = ltrim(Color::fromIntToHex($colors[1]), '#');
            $color3 = ltrim(Color::fromIntToHex($colors[2]), '#');
            $color4 = ltrim(Color::fromIntToHex($colors[3]), '#');
        }



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

    public static function searchPosts($query, $category = "0") {

        $firstchar = "";
        // Make var with the first char of the query
        if(!empty($query))
            $firstchar = $query[0];

        $selector = "";

        // If the first char is '@' you are searching for a person
        if($firstchar == "@") {
            $query = str_replace("@", "", $query);
            $selector = "user.username";
        } 
        //search for city using "!"+city
        else if ($firstchar == "!"){
            $query = str_replace("!", "", $query);
           $selector = "photo.city";
        }

        // Else searching post with a the query in description
        else if($query != ""){
            $selector = "photo.description";
        }
        

        $sql = "select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where $selector like '%" . $query . "%' and photo.inappropriate = 0 AND enable = 0";

        if($selector === ""){
            $sql = "select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.inappropriate = 0 AND enable = 0"; 
        }
        if(!empty($category)){
            $sql .= " AND category_id = " . $category;
         }
        $sql .= " order by id desc LIMIT 15";

        $conn = Db::getConnection();
        $statement =  $conn->prepare($sql);
        $statement->bindParam(":selector", $selector);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public static function getAllPosts($userId, $hashtags) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select photo.*, user.username, photo.id from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) and photo.description REGEXP :hashtags and photo.inappropriate = 0 and enable = 0 order by id desc limit 15");
        $statement->bindParam(":user_id", $userId);
        $statement->bindParam(":hashtags", $hashtags);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public static function getPostsByTag($tag) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.description like '%" . '#' . $tag . "%' and photo.inappropriate = 0 AND enable = 0 order by id desc LIMIT 15");
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


    public static function getPostById(int $post) {
        $conn = Db::getConnection();
            try{
                $statement = $conn->prepare("select * from photo where id = :id AND enable = 0");
                $statement->bindParam(":id", $post);
                $statement->execute();
                $post = $statement->fetch(PDO::FETCH_ASSOC);
                return $post;
            } catch (\PDOException $e){
                // Log to error file
                return false;
            }
        }
    
    public static function getCommentsByPostId(int $post) {
        $conn = Db::getConnection();
            try {
                $commentStatement = $conn->prepare("select comment.*, user.username from comment inner join user on comment.user_id = user.id where post_id = :postId and enable=0");
                $commentStatement->bindParam(":postId", $post);
                $commentStatement->execute();
                $comments = $commentStatement->fetchAll();
                return $comments;
            }catch (\PDOException $e){
                return false;
            }
        }
    
        
    public static function getAllEnabledPostsForUser(int $userId, int $limit=2) {
        $conn = Db::getConnection();
            try{
                 $statement = $conn->prepare("SELECT photo.*, user.username, photo.id FROM photo INNER JOIN user ON photo.user_id = user.id WHERE user_id IN ( SELECT following_id FROM followers WHERE user_id = :user_id ) AND photo.inappropriate = 0 AND enable=0 order by id desc limit 2");
                // $statement = self::$conn->prepare("SELECT photo.*, poster.id from photo JOIN `user` AS poster ON photo.user_id = poster.id WHERE user_id IN (SELECT following_id FROM followers WHERE user_id=) LIMIT $limit");
                //$statement = self::$conn->prepare("select photo.*, user.username, photo.id from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) and photo.inappropriate = 0 order by id desc limit 2");
                // $statement->bindparam(":limit", $limit);
                $statement->bindParam(":user_id", $userId);
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                //var_dump($userId);
                //die(json_encode($results));
                return $results;
            } catch (\PDOException $e){
                return false;
            }
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


        //get suggestions post_id's for new users by their like count (most liked posts)
        public static function getSuggestionsIds(){
            $conn = Db::getConnection();
            $statement = $conn->prepare("select post_id, COUNT(post_id) FROM `likes` WHERE liked_status = 1 GROUP BY post_id ORDER BY COUNT(post_id) desc limit 3");
            $statement->execute();
            $suggestions = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $suggestions;
        }

        //get suggestions posts data for new users
        public static function getSuggestionsPosts($suggestion_ids){
            //prepare query
            $values = implode(',', $suggestion_ids);

            //get result from DB
            $conn = Db::getConnection();
            $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.id IN (" . $values . ") order by id desc");
            $statement->execute();
            $suggestions = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $suggestions;
        }

    }
