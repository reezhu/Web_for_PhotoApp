<?php
$dom = new DOMDocument("1.0","utf-8");
header("Content-Type: text/plain"); 

$resources = $dom->createElement("resources");
$dom->appendChild($resources);

$root = $dom->createElement("string-array");
$resources->appendChild($root);

$name = $dom->createAttribute("name"); 
$root->appendChild($name); 
$nameValue = $dom->createTextNode("sticker"); 
$name->appendChild($nameValue); 


include("mysql.php");
$username = $_POST['username'];
$password = $_POST['password'];
$sql = "SELECT * FROM app_files where username = '".$username."'";
$result = $conn->query($sql);
		while($row = $result->fetch_assoc()) {
			$item=$dom->createElement('item', "@drawable/".$row['filename']."");
			$root->appendChild($item); 
		}
		
echo $dom->saveXML(); 
		

?>
