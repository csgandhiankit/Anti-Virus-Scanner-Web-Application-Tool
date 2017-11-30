<?php
include('config.php');

 $DB_NAME = 'Project174';
 $user_table = 'USER';
 $virus_table = 'VIRUS';
	
 $create_schema = "CREATE DATABASE IF NOT EXISTS ".$DB_NAME;
 $create_Db = mysql_query($create_schema, $db);

 if ($create_Db === TRUE) {
            //echo "<p>DB created successfully!<p>";
           // $result =  $db->query("use ".$DB_NAME);


         mysql_select_db( $DB_NAME );
        

          $create_user_table = "CREATE TABLE ". $DB_NAME. ".".$user_table."(
      	 	   UID INT NOT NULL AUTO_INCREMENT,
               FirstName VARCHAR(32) NOT NULL,
               LastName VARCHAR(32) NOT NULL,
               Email VARCHAR(32) NOT NULL,
               Pass VARCHAR(20) NOT NULL,
               Admin BOOLEAN,
               PRIMARY KEY(UID)
           )";

           $drop_user_table = "DROP TABLE IF EXISTS ". $user_table;

           $retval = mysql_query( $drop_user_table, $db );
         
          if(! $retval ) {
            	die('Could not delete table: ' . mysql_error());
         	}


           $val = mysql_query($create_user_table, $db);


           $create_virus_table = "CREATE TABLE ". $DB_NAME. ".".$virus_table."(
      	 	   vId INT NOT NULL AUTO_INCREMENT,
               UID INT NOT NULL,
               Admin BOOLEAN,
               PRIMARY KEY(vId)
           )";

           $drop_virus_table = "DROP TABLE IF EXISTS ". $virus_table;

          $retval1 = mysql_query( $drop_virus_table, $db );
         
          if(! $retval1 ) {
            	die('Could not delete table: ' . mysql_error());
         	}

           $val2 = mysql_query($create_virus_table, $db);

      }



?>