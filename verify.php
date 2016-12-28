<?php
         if(isset($_POST['update'])) {
            $dbhost = 'localhost';
            $dbuser = 'root';
            $dbpass = '';
            
            $conn = mysql_connect($dbhost, $dbuser, $dbpass);
            
            if(! $conn ) {
               die('Could not connect: ' . mysql_error());
            }
            
            $user_id = $_POST['user_id'];
            $verified_sms = $_POST['verified_sms'];
            
             //here
             
             $sql = "UPDATE user SET verified_sms = '$verified_sms', account_status = 1 WHERE user_id = $user_id";
            mysql_select_db('digitalid');
            $retval = mysql_query( $sql, $conn );
             
             
             $sel_query = "SELECT * FROM user WHERE user_id = $user_id";
             $results = mysql_query($sel_query);
             
    if ($row = mysql_fetch_assoc($results) ) {
        
        if ($_POST['verified_sms'] === $row['verify']) {
            $login_ok = true;
            
                
        }
    }
    
             if ($login_ok) {
                
                $response["success"] = 1;
                $response["message"] = "Card Updated!";
                echo json_encode($response);
             }
        
        else{       
                $response["success"] = 0;
                $response["message"] = "invalid Number";
                die(json_encode($response));
            
                mysql_close($conn);
        }
    
            
            
         }else {
            ?>
               <form method = "post" action = "<?php $_PHP_SELF ?>">
                  <table width = "400" border =" 0" cellspacing = "1" 
                     cellpadding = "2">
                  
                     <tr>
                        <td width = "100">verification code</td>
                        <td><input name = "verified_sms" type = "text" 
                           id = "verified_sms"></td>
                     </tr>
                  
                     <tr>
                        <td width = "100">User ID</td>
                        <td><input name = "user_id" type = "text" 
                           id = "user_id"></td>
                     </tr>
 
                     <tr>
                        <td width = "100"> </td>
                        <td>
                           <input name = "update" type = "submit" 
                              id = "update" value = "Update">
                        </td>
                     </tr>
                  
                  </table>
               </form>
            <?php
         }
      ?>
  