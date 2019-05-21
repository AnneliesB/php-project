<?php
require_once("bootstrap/bootstrap.php");
User::userLoggedIn();
//Get ID of logged in user so we can later fetch the posts of users he follows.
$userId = User::getUserId();
//Check if Search is used
if ((isset($_GET["category"]) && !empty($_GET["category"])) || (isset($_GET["query"]) && !empty($_GET["query"])))  { 
    $query = $_GET['query'];
    $results = Image::searchPosts($query, $_GET["category"]);
} 

else if (!empty($_GET['color'])) {
    $color = $_GET['color'];
    $results = Image::showImagesWithTheSameColor($color);
  
} else if(!empty($_GET['tag'])) {
    $tag = $_GET['tag'];
    $results = Image::getPostsByTag($tag);
    $follows = Follow::isFollowingHashTag($userId, $tag);

} else {
    // No Search
    // Show 20 posts of friends on startpage
    // Get hashtags that a user is following
    $hashtags = User::getFollowinghashtags($userId);
    // Get posts from DB and put them in $results
    $results = Image::getAllPosts($userId, $hashtags);

    //if no results -> not following anyone or any #
    if(empty($result)){
        //get some suggestions | top 5 most liked photo's
        $suggestions = Image::getSuggestionsIds(); //returns posts_id's and their like count
        
        //get only the post_id and store them into their own array
        $suggestion_ids = [];
        foreach($suggestions as $s){
            array_push($suggestion_ids, $s['post_id']);
        }
        //finally get the actual suggestion posts
        $suggestions = Image::getSuggestionsPosts($suggestion_ids);
                
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/cssgram.css">
    <title>Feed</title>
</head>
<body class="index">
<?php include_once("nav.incl.php"); ?>

<header>
    <form action="" method="GET">
        <div class="searchBar" id="search">
            <input type="text" id="query" name="query">
            <select name="category" id="category">
                <option value="0">None</option>
                <option value="1">Lineart</option>
                <option value="2">Emblems</option>
                <option value="3">Logotypes</option>
                <option value="4">Monogram Logo's</option>
                <option value="5">Brand Marks</option>
                <option value="6">Abstract Logo Marks</option>
                <option value="7">Mascots</option>
                <option value="8">Combination marks</option>
            </select>
            <input type="submit" name="submit" value="Search">
        </div>
    </form>

</header>

<div class="feed">
    <?php
    //Check if no post results (no friends or posts of friends found)
    if (!empty($results)) { 
        //Posts of friends found, display them with a loop

        // show search results
        if (!empty($_GET['query'])): ?>
            <div class="resultContainer">
                <p>Results for: <span class="queryResult"><?php echo $_GET['query'] ?></span></p>
            </div>
            <!-- Close if -->
        <?php endif;

        if (!empty($_GET['color'])): ?>
            <div class="resultContainer">
                <p>Results for: <span class="colorResult" style="background-color:<?php echo '#' .$_GET['color'] ?>"><?php echo $_GET['color'] ?></span></p>
            </div>
            <!-- Close if -->
        <?php endif;


        // If you search hashtags
        if (isset($_GET['tag'])): ?>
            <div class="hashtagBtnContainer">
                <a id="followHashtagBtn" data-tag="<?php echo '#' . $_GET['tag'] ?>" href=""><?php echo $follows . ' #' . $_GET['tag'] ?></a>
            </div>
        <!-- Close if -->
        <?php endif;
        

        foreach ($results as $result): ?>

            <div class="postContainer">

                <div class="postTopBar">
                    <div class="topBar--flex">
                        <a href="userProfile.php?username=<?php echo htmlspecialchars($result['username']); ?>">
                            <div class="postUsername"><?php echo htmlspecialchars($result['username']); ?></div>
                        </a>
                        <p class="timeAgo"><?php echo Image::timeAgo($result['time']); ?></p>
                    </div>

                    <div class="topBar--flex topBar--report">
                    <?php if (User::userHasReported($result['id'], $userId) == true): ?>
                        <a href="#" data-id="<?php echo $result['id'] ?>" class="inappropriate inappropriatedLink">
                            <img src="images/report.svg" alt="grey button" class="inappropriateIcon">
                        </a>

                    <?php else: ?>
                        <a href="#" data-id="<?php echo $result['id'] ?>" class="inappropriate">
                            <img src="images/report.svg" alt="red button" class="inappropriateIcon">
                        </a>
                    <?php endif ?>
                    </div>

                </div>


                    <a href="details.php?id=<?php echo $result['id']; ?>">
                        <div class="indexFilter">
                            <div class="<?php echo $result['filter']; ?>">
                                <img class="postImg" src="images/<?php echo $result['url_cropped'] ?>">
                            </div>
                        </div>
                    </a>



                <p class="postDescription"><?php echo preg_replace( '/\#([A-Za-z0-9]*)/is', ' <a href="index.php?tag=$1" class="hashtag">#$1</a> ', htmlspecialchars($result['description']));?></p>

                <div class="postStats">
                    <div>
                        <?php if (Like::userHasLiked($result['id'], $userId) == true) : ?>
                            <a href="#" data-id="<?php echo $result['id'] ?>" class="like"><img
                                        class="icon postLikeIcon"
                                        src="images/liked.svg"
                                        alt="like icon"></a>
                        <?php else: ?>
                            <a href="#" data-id="<?php echo $result['id'] ?>" class="like"><img
                                        class="icon postLikeIcon"
                                        src="images/like.svg"
                                        alt="like icon"></a>
                        <?php endif ?>

                        <p class="postLikes"><?php echo Like::getLikeAmount($result['id']); ?></p>
                    </div>
                    <div class="colorBlock">
                        <a href="index.php?color=<?php echo $result['color1']; ?>"
                           style="background-color:<?php echo "#" . $result['color1'] ?>;" class="colorBtn">
                            <p><?php echo $result['color1'] ?></p></a>
                        <a href="index.php?color=<?php echo $result['color2']; ?>"
                           style="background-color:<?php echo "#" . $result['color2'] ?>;" class="colorBtn">
                            <p><?php echo $result['color2'] ?></p></a>
                        <a href="index.php?color=<?php echo $result['color3']; ?>"
                           style="background-color:<?php echo "#" . $result['color3'] ?>;" class="colorBtn">
                            <p><?php echo $result['color3'] ?></p></a>
                        <a href="index.php?color=<?php echo $result['color4']; ?>"
                           style="background-color:<?php echo "#" . $result['color4'] ?>;" class="colorBtn">
                            <p><?php echo $result['color4'] ?></p></a>
                    </div>
                    <div>
                        <p class="postComments">0<?php //echo number of comments ?></p>
                        <img class="icon postCommentIcon" src="images/comment.svg" alt="comments icon">
                    </div>
                </div>


                <div id="<?php echo $result['id'] ?>">
                    <input class="commentInput" type="text" name="comment" placeholder="comment...">
                    <input class="commentBtn" type="button" value="Post" data-id= "<?php echo $result['id']; ?>">
                </div>


            </div>


        <?php endforeach; ?>

        <div class="loadMoreContainer">
            <a>
                <div class="loadMoreBtn grow">Load More</div>
            </a>
        </div>

    <?php } //Closing if
    else { //No posts of friends found, show empty state message and suggestions?>
        <div class="postContainer">
            <p>Try following friends and other users to see what they have been up to!</p>
            <h4>Here are some popular posts to get you started 🚀 </h4>
        </div>
        
        <?php foreach ($suggestions as $s): ?>

            <div class="postContainer">

                <div class="postTopBar">
                    <div class="topBar--flex">
                        <a href="userProfile.php?username=<?php echo htmlspecialchars($s['username']); ?>">
                            <div class="postUsername"><?php echo htmlspecialchars($s['username']); ?></div>
                        </a>
                        <p class="timeAgo"><?php echo Image::timeAgo($s['time']); ?></p>
                    </div>

                    <div class="topBar--flex topBar--report">
                    <?php if (User::userHasReported($s['id'], $userId) == true): ?>
                        <a href="#" data-id="<?php echo $s['id'] ?>" class="inappropriate inappropriatedLink">
                            <img src="images/report.svg" alt="grey button" class="inappropriateIcon">
                        </a>

                    <?php else: ?>
                        <a href="#" data-id="<?php echo $s['id'] ?>" class="inappropriate">
                            <img src="images/report.svg" alt="red button" class="inappropriateIcon">
                        </a>
                    <?php endif ?>
                    </div>

                </div>


                    <a href="details.php?id=<?php echo $s['id']; ?>">
                        <div class="indexFilter">
                            <div class="<?php echo $s['filter']; ?>">
                                <img class="postImg" src="images/<?php echo $s['url_cropped'] ?>">
                            </div>
                        </div>
                    </a>



                <p class="postDescription"><?php echo preg_replace( '/\#([A-Za-z0-9]*)/is', ' <a href="index.php?tag=$1" class="hashtag">#$1</a> ', htmlspecialchars($s['description']));?></p>

                <div class="postStats">
                    <div>
                        <?php if (Like::userHasLiked($s['id'], $userId) == true) : ?>
                            <a href="#" data-id="<?php echo $s['id'] ?>" class="like"><img
                                        class="icon postLikeIcon"
                                        src="images/liked.svg"
                                        alt="like icon"></a>
                        <?php else: ?>
                            <a href="#" data-id="<?php echo $s['id'] ?>" class="like"><img
                                        class="icon postLikeIcon"
                                        src="images/like.svg"
                                        alt="like icon"></a>
                        <?php endif ?>

                        <p class="postLikes"><?php echo Like::getLikeAmount($s['id']); ?></p>
                    </div>
                    <div class="colorBlock">
                        <a href="index.php?color=<?php echo $s['color1']; ?>"
                           style="background-color:<?php echo "#" . $s['color1'] ?>;" class="colorBtn">
                            <p><?php echo $s['color1'] ?></p></a>
                        <a href="index.php?color=<?php echo $s['color2']; ?>"
                           style="background-color:<?php echo "#" . $s['color2'] ?>;" class="colorBtn">
                            <p><?php echo $s['color2'] ?></p></a>
                        <a href="index.php?color=<?php echo $s['color3']; ?>"
                           style="background-color:<?php echo "#" . $s['color3'] ?>;" class="colorBtn">
                            <p><?php echo $s['color3'] ?></p></a>
                        <a href="index.php?color=<?php echo $s['color4']; ?>"
                           style="background-color:<?php echo "#" . $s['color4'] ?>;" class="colorBtn">
                            <p><?php echo $s['color4'] ?></p></a>
                    </div>
                    <div>
                        <p class="postComments">0<?php //echo number of comments ?></p>
                        <img class="icon postCommentIcon" src="images/comment.svg" alt="comments icon">
                    </div>
                </div>


                <div id="<?php echo $s['id'] ?>">
                    <input class="commentInput" type="text" name="comment" placeholder="comment...">
                    <input class="commentBtn" type="button" value="Post" data-id= "<?php echo $s['id']; ?>">
                </div>


            </div>


        <?php endforeach; ?>

    <?php } //Closing else ?>
</div>


    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="js/saveLikes.js"></script>
    <script src="js/loadMore.js"></script>
    <script src="js/inappropriate.js"></script>
    <script src="js/post.js"></script>
    <script src="js/navigation.js"></script>
    <script src="js/followHashtag.js"></script>


</body>
</html>