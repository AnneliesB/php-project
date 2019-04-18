<?php 
    require_once("bootstrap/bootstrap.php");

    $conn = Db::getConnection();
    //var_dump($results);

    if(!empty($_POST)){ 
        $query = $_POST['query'];
        $statement = $conn->prepare("select description, url from photo where description like '%". $query ."%'"); 
        $statement->execute();        
    }

    else {
        $statement = $conn->prepare("select description, url from photo");
        $statement->execute();        
    }

    $results = $statement->fetchAll();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Feed</title>
</head>
<body>

    <header>
        <form action="" method="POST">
            <div class="formField">
                <input type="text" id="query" name="query">
                <input  type="submit" name="submit" value="Search"> 
            </div>
        </form>
    
    </header>

    <main>
        <?php foreach($results as $result): ?>
            <article>    
                <img src="images/<?php echo $result['url'] ?>" alt="">    
                <p><?php echo $result['description'] ?></p>
            </article>
        <?php endforeach;?>
    
    </main>

    <footer>
        <p> &copy; Copyright IMDSTAGRAM 2019 </p>
    </footer>



    
</body>
</html>