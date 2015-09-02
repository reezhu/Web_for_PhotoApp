<?php

//load and connect to MySQL database stuff
require("config.inc.php");

if (!empty($_SESSION)) {
    //gets user's info based off of a username.
    $query = " 
            SELECT 
                id, 
                username, 
                password
            FROM app_user 
            WHERE 
                username = :username 
        ";
    
    $query_params = array(
        ':username' => $_SESSION['username']
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
	$login_ok = false;
    
    //fetching all the rows from the query
    $row = $stmt->fetch();
    if ($row) {
        //if we encrypted the password, we would unencrypt it here, but in our case we just
        //compare the two passwords
        if ($_SESSION['password'] === $row['password']) {
            $login_ok = true;
        }
    }
    
    // If the user logged in successfully, then we send them to the private members-only page 
    // Otherwise, we display a login failed message and show the login form again 
    if ($login_ok) {
		$response["success"] = 1;
		$response["sticker"] = array();
		$sql = "SELECT * FROM app_file where username = '".$_SESSION['username']."'";
		$result = $db->query($sql);
		// output data of each row
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			array_push($response["sticker"],$row["image_id"]);
			//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
		}
		
		die(json_encode($response));

    } else {
        $response["success"] = 0;
        $response["message"] = "Invalid Credentials!";
        die(json_encode($response));
    }
} else {
	$response["success"] = 0;
    $response["message"] = "timeout,please login again";
    die(json_encode($response));
}

?> 
