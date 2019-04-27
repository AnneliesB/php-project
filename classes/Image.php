<?php 
    require_once("bootstrap/bootstrap.php");

    class Image {
        public static function getPostId(){ 
            $statement = $conn->prepare("select id from photo order by id desc limit 1");
            $statement->execute();
            $photo = $statement->fetch(PDO::FETCH_ASSOC);
            $post_id = $photo['id'] + 1;

            // return current post id
            return $post_id;
        }

        public function saveImageToDb() {

        }


        public function saveCroppedImage() {

        }


    }