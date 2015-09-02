<?php session_start(); ?>
<?php 
$servername = "dragon.ukc.ac.uk";
$username = "wz57";
$password = "eleamwa";
$dbname = "wz57";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else{
	$db = mysql_connect($servername,$username,$password);
	mysql_select_db($dbname, $db);
}

//上传文件的路径
$dir = 'drawables';
/*
$_FILES:用在当需要上传二进制文件的地方,获得该文件的相关信息
$_FILES['userfile']['name'] 客户端机器文件的原名称。 
$_FILES['userfile']['type'] 文件的 MIME 类型，需要浏览器提供该信息的支持，例如“image/gif” 
$_FILES['userfile']['size'] 已上传文件的大小，单位为字节
$_FILES['userfile']['tmp_name'] 文件被上传后在服务端储存的临时文件名,注意不要写成了$_FILES['userfile']['temp_name']很容易写错的，虽然tmp就是代表临时的意思，但是这里用的缩写
$_FILES['userfile']['error'] 和该文件上传相关的错误代码。['error'] 
*/
if($_FILES['uploadfile']['error'] != UPLOAD_ERR_OK)
{
    switch($_FILES['uploadfile']['error'])
    {
        case UPLOAD_ERR_INI_SIZE: //其值为 1，上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值
            die('The upload file exceeds the upload_max_filesize directive in php.ini');
        break;
        case UPLOAD_ERR_FORM_SIZE: //其值为 2，上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值
            die('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.');
        break;
        case UPLOAD_ERR_PARTIAL: //其值为 3，文件只有部分被上传
            die('The uploaded file was only partially uploaded.');
        break;
        case UPLOAD_ERR_NO_FILE: //其值为 4，没有文件被上传
            die('No file was uploaded.');
        break;
        case UPLOAD_ERR_NO_TMP_DIR: //其值为 6，找不到临时文件夹
            die('The server is missing a temporary folder.');
        break;
        case UPLOAD_ERR_CANT_WRITE: //其值为 7，文件写入失败
            die('The server failed to write the uploaded file to disk.');
        break;
        case UPLOAD_ERR_EXTENSION: //其他异常
            die('File upload stopped by extension.');
        break;
    }
}
if($_POST['information']==null){
	$image_info = "No info";
}else{
	$image_info = $_POST['information'];
}

$image_username = $_SESSION["username"];
$image_date = date('Y-m-D');
/*getimagesize方法返回一个数组，
$width : 索引 0 包含图像宽度的像素值，
$height : 索引 1 包含图像高度的像素值，
$type : 索引 2 是图像类型的标记：
= GIF，2 = JPG， 3 = PNG， 4 = SWF， 5 = PSD， 6 = BMP， 
= TIFF(intel byte order)，8 = TIFF(motorola byte order)，
= JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM，
$attr : 索引 3 是文本字符串，内容为“height="yyy" width="xxx"”，可直接用于 IMG 标记
*/

list($width,$height,$type,$attr) = getimagesize($_FILES['uploadfile']['tmp_name']);

//imagecreatefromgXXX方法从一个url路径中创建一个新的图片
switch($type)
{  
    case IMAGETYPE_PNG:
        $image = imagecreatefrompng($_FILES['uploadfile']['tmp_name']) or die('The file you upload was not supported filetype');
        $ext = '.png';
    break;    
    default    :
        die('The file you uploaded was not a supported filetype.');
}

$query = 'insert into app_file(uploader,filename,info) values ("'.$_SESSION["username"].'","'.$_FILES['uploadfile']['name'].'","'.$image_info.'")';
mysql_query($query , $db) or die(mysql_error($db));
$last_id = mysql_insert_id();
//用写入的id作为图片的名字，避免同名的文件存放在同一目录中
$imagename = $last_id.$ext;
$query = 'insert into app_match(file_id,username) values ("'.$last_id.'","'.$_SESSION["username"].'")';
mysql_query($query , $db) or die(mysql_error($db));
//有url指定的图片创建图片并保存到指定目录
switch($type)
{
    case IMAGETYPE_PNG:
        imagepng($image,$dir.'/'.$imagename);
    break;
}
//销毁由url生成的图片
imagedestroy($image);
?>

<html>
    <head>
        <title></title>
    </head>
    <body>
        <h1>So how does it feel to be famous</h1>
        <p>Here is the picture you just upload to servers</p>
        <img src="drawables/<?php echo $imagename;?>" alt="<?php echo $imagename;?>" />
        <table>
        <tr>
            <td>Image save as:</td><td><?php echo $imagename?></td>
			</tr><tr>
            <td>Image type:</td><td><?php echo $ext?></td>
			</tr><tr>
            <td>Height:</td><td><?php echo $height?></td>
			</tr><tr>
            <td>Width:</td><td><?php echo $width?></td>
			</tr><tr>
            <td>Upload date:</td><td><?php echo $image_date?></td>
        </tr>
        </table>
		<h2><a href="operation.php">Back</a></h2>
    </body>
</html>