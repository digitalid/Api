<?php
         if(isset($_POST['update'])) {
            $dbhost = 'localhost';
            $dbuser = 'root';
            $dbpass = '';
            
            $conn = mysql_connect($dbhost, $dbuser, $dbpass);
            
            if(! $conn ) {
               die('Could not connect: ' . mysql_error());
            }
            
            $id_number = $_POST['id_number'];
            $id_name = $_POST['id_name'];
            $expiry_date = $_POST['expiry_date']; 
            $nationality = $_POST['nationality'];
          
             
             $sql = "UPDATE card SET id_name = '$id_name', expiry_date = '$expiry_date', nationality = '$nationality' WHERE id_number = $id_number";
            mysql_select_db('digitalid');
            $retval = mysql_query( $sql, $conn );
            
            if(! $retval ) {
               //die('Could not update data: ' . mysql_error());
                $response["success"] = 0;
                $response["message"] = "Select card to Update";
                die(json_encode($response));
            }
            //echo "Updated data successfully\n";
                $response["success"] = 1;
                $response["message"] = "Card Updated!";
                echo json_encode($response);
            
                mysql_close($conn);
         }else {
            ?>
               <form method = "post" action = "<?php $_PHP_SELF ?>">
                  <table width = "400" border =" 0" cellspacing = "1" 
                     cellpadding = "2">
                  
                     <tr>
                        <td width = "100">Card ID Number</td>
                        <td><input name = "id_number" type = "text" 
                           id = "id_number"></td>
                     </tr>
                  
                     <tr>
                        <td width = "100">ID Name</td>
                        <td><input name = "id_name" type = "text" 
                           id = "id_name"></td>
                     </tr>
                      
                       <tr>
                        <td width = "100"> expiry Date</td>
                        <td><input name = "expiry_date" type = "date" 
                           id = "expiry_date"></td>
                        </tr>
                      
                       <tr>
                        <td width = "100">Nationality</td>
                        <td><input name = "nationality" type = "text" 
                           id = "nationality"></td>
                     </tr>
                  
                     <tr>
                        <td width = "100"> </td>
                        <td> </td>
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
  