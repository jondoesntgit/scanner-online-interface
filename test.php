<?php

require_once('extract_id.php');

foreach(array('eigentalks','eigenextras') as $type_of_eigen) 
  $files = glob('uploads/' . $type_of_eigen . '/*.csv', GLOB_BRACE);
  foreach($files as $file) {
    // Set short filename for display
    $short_filename = $file;
    if (strpos($short_filename, '/') !== FALSE)
      $short_filename = substr($short_filename, strrpos($short_filename, '/') + 1);
    if (strpos($short_filename, '.') !== FALSE) 
      $short_filename = substr($short_filename, 0, strpos($short_filename, "."));
    // Open file
    $contents = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $csvRows = array_map('str_getcsv', $contents);    
    // Give students credit that were in file
    foreach($csvRows as $row) 
    {
      $id = extract_id($row);
print_r($row);
echo $id . '<br>';
      // Is this the student's ID?
      /*if (strcmp($id,$studentid)==0)
      {
	// Is this scan already credited to the student
	if (!in_array($short_filename,$$type_of_eigen))
	{
	  array_push($$type_of_eigen,$short_filename);
	}
      }
*/
    }
  }
