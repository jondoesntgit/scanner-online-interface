<?php
header('Refresh: 10;url=index.php?show=files');
$target_dir = "uploads/";
if (isset($_POST['filename'])){
  $filename = $_POST['filename'];
  if (strpos($filename, '/') !== FALSE)
    $filename = substr($filename, strrpos($filename, '/') + 1);

  if (strpos($filename, '.') !== FALSE) {
    $filename = substr($filename, 0, strpos($filename, "."));
}
  $filename = $filename . '.csv';
} else {
  $uploadOk = 0;
  echo "<h1>Failure</h1>No filename specified";
}
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $_POST['purpose'] . '/' . $filename;
$uploadOk = 1;
/*$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
*/
//}
// Check if file already exists
if (file_exists($target_file)) {
    echo "<h1>Failure</h1>Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "<h1>Failure</h1>Sorry, your file is too large.";
    $uploadOk = 0;
}

// Check for identical file
$directory = $target_dir . $_POST['purpose'];
$cmd = 'diff -qs --from-file ' . $_FILES["fileToUpload"]["tmp_name"] . ' ' . $directory . '/* | grep "identical"';
//echo $cmd . '<br>';

exec($cmd,$diffoutput);
if(count($diffoutput) > 0){
  echo "<h1>Failure</h1>File is not unique. The contents are identical to another file:<br><pre>";
  foreach($diffoutput as $line)
    echo $line . "<br>";
  echo "</pre>";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<p>Please go back and try uploading again</p>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<h1>Success</h1>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded as " . $filename . ".";
	echo "<br><br>Running diff test...<br><br>";
?><pre><?php

?> </pre> <?php
    } else {
        echo "<h1>Failure</h1>Sorry, there was an unidentified error in the final stage of uploading your file.";
    }
}


echo '<br>You will be redirected in 10 seconds...</br>';

?>
