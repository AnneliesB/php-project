<?php
    require_once("../bootstrap/bootstrap.php");

    //Get passed data from axios call
    $data = json_decode(file_get_contents("php://input"), true);
    //get start point of new posts to load
    $startpoint = $data['shownPosts'];
    //get searchquery (we'll check if there is one passed later)
    $searchQuery = $data['searchQuery'];
    $colorSearch = $data['colorSearch'];

    //Get connection with DB
    $conn = Db::getConnection();

    //Get user ID of currently logged in user
    $user_id = User::getUserId();

    //check if we are on a search results page (to show only search results and not actual index posts on loading more)
    if( $searchQuery !== null){

        //we are on a search results page, show more search results!
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.description like '%". $searchQuery ."%' and photo.inappropriate = 0 order by id desc LIMIT $startpoint, 15"); 
        $statement->execute();   
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        //loop over all posts in results and add extra info we need into the results array for the js post template
        for($i = 0; $i < sizeof($results); $i++){

            //escape special chars for XSS scripting before passing it back to JSON
            $username = $results[$i]['username'];
            $username = htmlspecialchars($username);
            $results[$i]['username'] = $username;
            
            //escape special chars for XSS scripting before passing it back to JSON
            $description = $results[$i]['description'];
            $description = htmlspecialchars($description);
            $results[$i]['description'] = $description;
            
            //get the amount of likes for each post and update the results array
            $likeAmount = Like::getLikeAmount($results[$i]['id']);
            $results[$i]["likeAmount"] = $likeAmount;

            //get the likedstatus for this user on each post and update the results array
            $hasliked = Like::userHasLiked($results[$i]['id'], $user_id);
            $results[$i]["hasLiked"] = $hasliked;

            // time ago
            $ago = Image::timeAgo($results[$i]['time']);
            $results[$i]['ago'] = $ago;


            // get the reported status
            $hasReported = User::userHasReported($result[$i]['id'], $user_id);
            $results[$i]["hasReported"] = $hasReported;
        }

    }else if ($colorSearch !== null){

        //we are on a search results page, show more search results!
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.color1 like '%" . $colorSearch . "%' OR  photo.color2 like '%" . $colorSearch . "%' OR  photo.color3 like '%" . $colorSearch . "%' OR  photo.color4 like '%" . $colorSearch . "%' and photo.inappropriate = 0 order by id desc LIMIT $startpoint, 15");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        //loop over all posts in results and add extra info we need into the results array for the js post template
        for($i = 0; $i < sizeof($results); $i++){

            //escape special chars for XSS scripting before passing it back to JSON
            $username = $results[$i]['username'];
            $username = htmlspecialchars($username);
            $results[$i]['username'] = $username;

            //escape special chars for XSS scripting before passing it back to JSON
            $description = $results[$i]['description'];
            $description = htmlspecialchars($description);
            $results[$i]['description'] = $description;

            //get the amount of likes for each post and update the results array
            $likeAmount = Like::getLikeAmount($results[$i]['id']);
            $results[$i]["likeAmount"] = $likeAmount;

            //get the likedstatus for this user on each post and update the results array
            $hasliked = Like::userHasLiked($results[$i]['id'], $user_id);
            $results[$i]["hasLiked"] = $hasliked;

            $ago = Image::timeAgo($results[$i]['time']);
            $results[$i]['ago'] = $ago;
        }

    } else {

        //We are not on a search results page => Get posts from DB and put them in $results
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) and photo.inappropriate = 0 order by id desc limit $startpoint, 15");
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        //loop over all posts in results and add extra info we need into the results array for the js post template
        for($i = 0; $i < sizeof($results); $i++){

            //escape special chars for XSS scripting before passing it back to JSON
            $username = $results[$i]['username'];
            $username = htmlspecialchars($username);
            $results[$i]['username'] = $username;
            
            //escape special chars for XSS scripting before passing it back to JSON
            $description = $results[$i]['description'];
            $description = htmlspecialchars($description);
            $results[$i]['description'] = $description;

            //get the amount of likes for each post and update the results array
            $likeAmount = Like::getLikeAmount($results[$i]['id']);
            $results[$i]["likeAmount"] = $likeAmount;

            //get the likedstatus for this user on each post and update the results array
            $hasliked = Like::userHasLiked($results[$i]['id'], $user_id);
            $results[$i]["hasLiked"] = $hasliked;

            // get time ago for each post
            $ago = Image::timeAgo($results[$i]['time']);
            $results[$i]['ago'] = $ago;
        }

    }
    


    echo json_encode($results);