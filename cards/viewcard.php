<?php

/*
Our "config.inc.php" file connects to database every time we include or require
it within a php script.  Since we want this script to add a new user to our db,
we will be talking with our database, and therefore,
let's require the connection to happen:
*/
require("config.inc.php");

$userid = $_REQUEST['user_id'];

//initial query
$query = "Select * FROM card where user_id = $userid";

//execute query
try {
    $stmt   = $db->prepare($query);
    //$query_params
    $result = $stmt->execute();
}
catch (PDOException $ex) {
    $response["success"] = 0;
    $response["message"] = "Please login!";
    die(json_encode($response));
}

// Finally, we can retrieve all of the found rows into an array using fetchAll 
$rows = $stmt->fetchAll();


if ($rows) {
    $response["success"] = 1;
    $response["message"] = "Post Available!";
    $response["posts"]   = array();
    
    foreach ($rows as $row) {
        $post = array();
	
        $post["id_number"] = $row["id_number"];
        $post["id_name"]    = $row["id_name"];
        $post["expiry_date"]  = $row["expiry_date"];
        $post["nationality"]  = $row["nationality"];
        $post["user_id"]  = $row["user_id"];
        
        //update our repsonse JSON data
        array_push($response["posts"], $post);
    }
    
    // echoing JSON response
    echo json_encode($response);
    
    
} else {
    $response["success"] = 0;
    $response["message"] = "No Card Available!";
    die(json_encode($response));
}

?>