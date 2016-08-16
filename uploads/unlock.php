<?php
$folders = array('eigentalks/','eigenextras/','generalphysics/','rosters/');
foreach($folders as $folder) {
  $files = scandir($folder);
  foreach($files as $file) {
    echo "unlocked ".$folder . $file ."<br>";
    if (strlen($file)>2) // Ignore . and ..
    chmod($folder . $file,0666);
    if (strlen($file)<=2)
    chmod($folder . $file,0777);
  }
}
?>
