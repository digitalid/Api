<?php

//load and connect to MySQL database stuff
require("config.inc.php");

if (!empty($_POST)) {
	//initial query
	$query = "INSERT INTO card (id_number, id_name, expiry_date, nationality, user_id) VALUES (:id_number, :id_name, :expiry_date, :nationality, :user_id) ";

    //Update query
    $query_params = array(
        ':id_number' => $_POST['id_number'],
        ':id_name' => $_POST['id_name'],
        ':expiry_date' => $_POST['expiry_date'],
        ':nationality' => $_POST['nationality'],
        ':user_id' => $_POST['user_id'],
        
    );
  
	//execute query
    try {
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        // For testing, you could use a die and message. 
        //die("Failed to run query: " . $ex->getMessage());
        
        //or just use this use this one:
        $response["success"] = 0;
        $response["message"] = "Database Error. Couldn't add post!";
        die(json_encode($response));
    }

    $response["success"] = 1;
    $response["message"] = "Card Successfully Added!";
    echo json_encode($response);
   
} else {
?>
		<h1>Add Card</h1> 
		<form action="addcard.php" method="post"> 
		    idNumber:<br /> 
		    <input type="text" name="id_number" placeholder="idNumber" /> 
		    <br /><br /> 
		     idName:<br /> 
		    <input type="text" name="id_name" placeholder="ID Name" /> 
		    <br /><br /> 
		     ExpiryDate:<br /> 
		    <input type="text" name="expiry_date" placeholder="expiry date" /> 
		    <br /><br /> 
		    nationality:<br /> 
		    <input type="text" name="nationality" placeholder="nationality" /> 
		    <br /><br />
			userID:<br /> 
		    <input type="text" name="user_id" placeholder="user id" /> 
		    <br /><br />
		     
		    <input type="submit" value="Add report" /> 
		</form> 
	<?php
}

?> 