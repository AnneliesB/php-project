<?php
    require_once("../bootstrap/bootstrap.php");

    //Get passed data from axios call
    $data = json_decode(file_get_contents("php://input"), true);
    //get query from the posted data AND add the SQL wildcard chars around them already
    $query = "%" . $data['query'] . "%";

    //Get connection with DB
    $conn = Db::getConnection();

    //Select lat, lng and url_cropped from all photos WHERE 'query' is present in description
    $statement = $conn->prepare("select id, lat, lng, url_cropped from photo where description like ? ");
    $statement->bindParam("1", $query);
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