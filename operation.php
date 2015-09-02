<?php session_start(); ?>
<head>
<meta charset="utf-8">
<title>Operation</title>

<html>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Upload image*</td>
                    <td><input type="file" name="uploadfile"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <small><em> * Only acceptable image format: PNG.</em></small>
                    </td>
                </tr>
                <tr>
                    <td>Image information</td>
                    <td><input type="text" name="information"/></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center;">
                        <input type="submit" name="submit" value="Upload" />
                    </td>
                </tr>
            </table>
        </form>
<?php
if($_SESSION["username"]!=null&&$_SESSION["password"]!=null){
	

$servername = "dragon.ukc.ac.uk";
$username = "wz57";
$password = "eleamwa";
$dbname = "wz57";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM app_match where username='".$_SESSION["username"]."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '
			<a href="drawables/'.$row['file_id'].'.png" download="'.$row['file_id'].'">
			<img border="0" src="drawables/'.$row['file_id'].'.png" alt="'.$row['file_id'].'.png" width="50" height="50">
			</a>
			';
    }
} else {
    echo "0 results";
}
$conn->close();	
}
else
{
        echo 'Invalid session! Please login again!';
        //echo '<meta http-equiv=REFRESH CONTENT=1;url=index.html>';
}
?>

<body>
</html>