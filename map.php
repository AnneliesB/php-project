<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.54.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.54.0/mapbox-gl.css' rel='stylesheet' />
    
    <title>IMDSTAGRAM - MAP</title>
</head>
<body class="defaultAlign">
    <?php include_once("nav.incl.php"); ?>

    <form class="searchMap" action="" method="GET">
        <div class="searchBar" id="search">
            <input type="text" id="searchMapInput" name="query">
            <input type="submit" id="searchMapBtn" name="submit" value="Search">
        </div>
    </form>
    
    <div class="map" id="map"></div>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="js/imageMap.js"></script>
    <script src="js/navigation.js"></script>
</body>
</html>