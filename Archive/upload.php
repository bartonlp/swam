<html>
<head></head>
<body>

<?php 
// $Id: $
// In PHP earlier than 4.1.0, $HTTP_POST_FILES  should be used instead of $_FILES.

if(file_exists("/swam/johnz/www/html/swam/pictures/$userfile_name")) {

	print("<h1 align=center>$userfile_name already exists in the upload directory</h1>\n");
	$newname = tempnam("/swam/johnz/www/html/swam/pictures/", "$userfile_name");
	$base = basename($newname);
	print("<h2 align=center>Renaming existing file to $base</h2><hr>\n");
	rename("/swam/johnz/www/html/swam/pictures/$userfile_name", $newname);
	
}

if(!move_uploaded_file($_FILES['userfile']['tmp_name'], "/swam/johnz/www/html/swam/pictures/$userfile_name")) {
	print("<h1 align=center>File Transfer Error</h1>\n");
	// NOTE THAT THIS Number should match the MAX_FILE_SIZE in upload.html
	print("<p align=center>The max size for uploads is 100 Meg. Check the file size.</p>\n");
} else {
	print("<h1 align=center>File Transfer Okay</h1>\n");
	print("<h2 align=center>name=$userfile_name<br>type=$userfile_type<br>size=$userfile_size</h2>\n");
}
?>

</body>
</html>
