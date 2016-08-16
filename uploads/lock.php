<?php
$folders = array('eigentalks/','eigenextras/','generalphysics/');
foreach($folders as $folder) {
  $files = scandir($folder);
  foreach($files as $file) {
    echo "locked ".$folder . $file ."<br>";
    if (strlen($file)>2) // Ignore . and ..
    chmod($folder . $file,0644);
    if (strlen($file)<=2)
    chmod($folder . $file,0755);
  }
}
?>
