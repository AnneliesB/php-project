<?php 
    require_once("bootstrap/bootstrap.php");

    $conn = Db::getConnection();
    //var_dump($results);

    if(!empty($_POST)){ 
        $query = $_POST['query'];
        $statement = $conn->prepare("select * from photo where description like '%". $query ."%'"); 
        $statement->execute();        
    }

    else {
        $statement = $conn->prepare("select description, url from photo");
        $statement->execute();        
    }

    if($statement->rowCount() > 0){
        $results = $statement->fetchAll();
    }

    else {
        $error = "No results!";
    }

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
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
        <?php if (isset($error)): ?>
            <div class="formError">
                <p>
                    <?php echo $error ?>
                </p>
            </div>
        <?php endif; ?>


        <?php if(isset($results)): ?>
            <?php foreach($results as $result): ?>
                <article>    
                    <img src="images/<?php echo $result['url'] ?>" alt="">    
                    <p><?php echo $result['description'] ?></p>
                </article>
            <?php endforeach;?>
        <?php endif; ?>
    </main>

    <footer>
        <p> &copy; Copyright IMDSTAGRAM 2019 </p>
    </footer>

</body>
</html>