<?php
    require_once("../bootstrap/bootstrap.php");

    //Get connection with DB
    $conn = Db::getConnection();

    //Select lat, lng and url_cropped from all photos
    $statement = $conn->prepare("select lat, lng, url_cropped from photo");
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    //loop over results and escape specialchars in case someone XSS scripted our hidden form fields for lat and lng
    for($i = 0; $i < sizeof($results); $i++){

        $lat = $results[$i]['lat'];
        $lat = htmlspecialchars($lat);
        $results[$i]['lat'] = $lat;

        $lng = $results[$i]['lng'];
        $lng = htmlspecialchars($lng);
        $results[$i]['lng'] = $lng;

    }

    //pass back the results in JSON
    echo json_encode($results);


