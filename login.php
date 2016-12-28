<?php

//load and connect to MySQL database stuff
require("config.inc.php");

if (!empty($_POST)) {
    //gets user's info based off of a phonenumber.
    $query = " 
            SELECT 
                user_id, 
                phone_number,
                password                
            FROM user 
            WHERE 
                phone_number = :user_tel AND account_status = 1
        ";
    
    $query_params = array(
        ':user_tel' => $_POST['phone_number'],
       
    );
    
    try {
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        // For testing, you could use a die and message. 
        //die("Failed to run query: " . $ex->getMessage());
        
        //or just use this use this one to product JSON data:
        $response["success"] = 0;
        $response["message"] = "Database Error1. Please Try Again!";
        die(json_encode($response));
        
    }
    
    //This will be the variable to determine whether or not the user's information is correct.
    //we initialize it as false.
    $validated_info = false;
    
    //fetching all the rows from the query
    $row = $stmt->fetch();
    if ($row) {
        //if we encrypted the password, we would unencrypt it here, but in our case we just
        //compare the two passwords
        if (md5($_POST['password']) === $row['password']) {
            $login_ok = true;
        }
    }
    
    // If the user logged in successfully, then we send them to the private members-only page 
    // Otherwise, we display a login failed message and show the login form again 
    if ($login_ok) {
        $response["success"] = 1;
        $response["phone_number"] = $row['phone_number'];
        $response["password"] = $row['password'];
        $response["message"] = "Login successful!";
        die(json_encode($response));
    } else {
        $response["success"] = 0;
        $response["message"] = "Invalid Credentials!";
        die(json_encode($response));
    }
} else {
?>
		<h1>Login</h1> 
		<form action="login.php" method="post"> 
		    phone_number:<br /> 
		    <input type="text" name="phone_number" placeholder="tel" value="" /> 
		    <br /><br />
            
            password:<br /> 
		    <input type="text" name="password" placeholder="tel" value="" /> 
		    <br /><br />
		   
		    <input type="submit" value="Login" /> 
		</form> 
		
	<?php
}

?> 
