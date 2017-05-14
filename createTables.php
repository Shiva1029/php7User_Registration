<?php
require_once "db_connection.php";


$query = "CREATE TABLE IF NOT EXISTS Users (
		 		 id int(11) NOT NULL auto_increment,
				 fname varchar(255) NOT NULL,
				 lname varchar(255) NOT NULL,
				 gender enum('m','f') DEFAULT 'm', 
				 email varchar(255) NOT NULL,
				 password varchar(255) NOT NULL,
                 PRIMARY KEY  (id))";

if ($link -> query($query))
    echo "Success!";
else
    echo "Error!";

