<?php
$id =$_REQUEST['id_number'];

$con = mysql_connect("localhost","root","");
if (!$con)
{
die('Could not connect: ' . mysql_error());
}

mysql_select_db("digitalid", $con);

// sending query
$todel = mysql_query("DELETE FROM card WHERE id_number = '$id'");
 
if(!$_REQUEST['id_number']){
 
    $response["success"] = 0;
    $response["message"] = "Select card to delete";
    die(json_encode($response));
}
    $response["success"] = 1;
    $response["message"] = "Card deleted!";
    echo json_encode($response);

?>