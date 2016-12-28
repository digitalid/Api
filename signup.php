<?php
/*
Our "config.inc.php" file connects to database every time we include or require
it within a php script.  Since we want this script to add a new user to our db,
we will be talking with our database, and therefore,
let's require the connection to happen:
*/
require("config.inc.php");

require_once('AfricasTalkingGateway.php');


// Specify your login credentials dont edit this section
$username = "digitalid";
$apikey  = "4750d3d8f654ab49efa140e7112cba400e56dd9224797525ea5e43ddf977af8b";


//if posted data is not empty
if (!empty($_POST)) {
    
    //set the random id length 
    $random_id_length = 4; 
    //generate a random id to be texted 
    $rnd_id = rand();
    //finally I take the first 4 characters from the $rnd_id 
    $rnd_id = Substr($rnd_id,0,$random_id_length); 
    /*If the phonenumber or password is empty when the user submits
    the form, the page will die.*/
    
    
    
    if (empty($_POST['phone_number']) || empty($_POST['password'])) {
        
        
        // Create some data that will be the JSON response 
        $response["success"] = 0;
        $response["message"] = "Please Enter your Phone Number and password.";
        
        /*die will kill the page and not execute any code below, it will also
        display the parameter... in this case the JSON data our mobile clients
        app will parse */
        die(json_encode($response));
    }
    
    //if the page hasn't died, we will check with our database to see if there is
    //already a user with the username specificed in the form.  ":user" is just
    //a blank variable that we will change before we execute the query.  We
    //do it this way to increase security, and defend against sql injections
    $query        = " SELECT 1 FROM user WHERE phone_number = :user";
    //now lets update what :user should be
    $query_params = array(
        ':user' => $_POST['phone_number']
    );
    
    //Now let's make run the query:
    try {
        // These two statements run the query against your database table. 
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        // For testing, you could use a die and message. 
        //die("Failed to run query: " . $ex->getMessage());
        
        //or just use this use this one to product JSON data:
        $response["success"] = 0;
        $response["message"] = "Couldn’t finish request. Please Try Again!";
        die(json_encode($response));
    }
    
    //fetch is an array of returned data.  If any data is returned,
    //we know that the username is already in use, so we murder our
    //page
    $row = $stmt->fetch();
    if ($row) {
        // For testing, you could use a die and message. 
        //die("This username is already in use");
        
        //You could comment out the above die and use this one:
        $response["success"] = 0;
        $response["message"] = "I'm sorry, this mobile number is already in use";
        die(json_encode($response));
    }
    
    
    
    // Specify the numbers that you want to send to in a comma-separated list
    $recipients= $_POST['phone_number'];

    // And of course we want our recipients to know what we really do
    $message= "Digital Identification security code: "."$rnd_id"." use this to finish verification";

    // Create a new instance of our awesome gateway class
    $gateway    = new AfricasTalkingGateway($username, $apikey);

    // Any gateway error will be captured by our custom Exception class below, 
    // so wrap the call in a try-catch block

    try 
    { 
  // That's it, hit send and we'll take care of the rest. 
  $results = $gateway->sendMessage($recipients, $message);
			
  foreach($results as $result) {
    // status is either "Success" or "error message"
  }
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error while sending: ".$e->getMessage();
}
    //If we have made it here without dying, then we are in the clear to 
    //create a new user.  Let's setup our new query to create a user.  
    //Again, to protect against sql injects, user tokens such as :user and :pass
    $query = "INSERT INTO user (phone_number, password, verify) VALUES ( :user, :password, :verify) ";
    
    //Again, we need to update our tokens with the actual data:
    $query_params = array(
        ':user' => $_POST['phone_number'],
        ':password' => md5($_POST['password']),
        ':verify' => $rnd_id
    );
    
    //time to run our query, and create the user
    try {
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        // For testing, you could use a die and message. 
        //die("Failed to run query: " . $ex->getMessage());
        
        //or just use this use this one:
        $response["success"] = 0;
        $response["message"] = "Couldn't Finish request. Please Try Again!";
        die(json_encode($response));
    }
    
    //If we have made it this far without dying, we have successfully added
    //a new user to our database.  We could do a few things here, such as 
    //redirect to the login page.  Instead we are going to echo out some
    //json data that will be read by the Android application, which will login
    //the user (or redirect to a different activity, I'm not sure yet..)
    $response["success"] = 1;
    $response["message"] = "Account Successfully created!";
    echo json_encode($response);
    
    //for a php webservice you could do a simple redirect and die.
    //header("Location: login.php"); 
    //die("Redirecting to login.php");
    
    
} else {
?>
	<h1>Register</h1> 
	<form action="signup.php" method="post"> 
	    phoneNumber:<br /> 
	    <input type="text" name="phone_number" value="" /> 
	    <br /><br /> 
	    
	    Password:<br /> 
	    <input type="text" name="password" value="" /> 
	    <br /><br /> 
	    
	    <input type="submit" value="Register New User" /> 
	</form>
	<?php
}

?>