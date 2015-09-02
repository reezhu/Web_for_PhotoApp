<?php 

    // These variables define the connection information for your MySQL database 
	$host = "dragon.ukc.ac.uk"; 
	$dbname = "wz57";     
	$username = "wz57"; 
    $password = "eleamwa"; 
    
	$conn = new mysqli($host, $username, $password, $dbname);
    if($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	}

?>