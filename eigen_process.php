<?php
/* Headers for downloading file */

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=eigenreport.csv');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

//header('Content-Length: ' . filesize($file));

/* Get roster file */
if (isset($_POST['roster_file'])) {
	echo $_POST['roster_file'];
  if (strpos($_POST['roster_file'], '/') == FALSE){
    if (strpos($_POST['roster_file'], '.') == FALSE){
      $roster_file = 'uploads/rosters/' . $_POST['roster_file'] . '.csv';
    } else { die('Invalid roster file'); }
  } else { die('Invalid roster file'); }
} else { die('No roster file set'); }



$contents = file($roster_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$csvRows = array_map('str_getcsv', $contents);
$students = array();
foreach($csvRows as $row) {
  $id = $row[0];
  //Remove header
  if (strcmp($id,'Id') == 0)
    continue;
  // Remove any extra spaces on front
  if(!ctype_digit(substr($id,0,1)))
    $id = substr($id, 1);
  // Remove any extra spaces on end
  if(!ctype_digit(substr($id,0,-1)))
    $id = substr($row[0], 0,-1);
  $students[$id] = array(
    "id" => $id,
    "last_name" => $row[1],
    "first_name" => $row[2],
    "eigentalks" => array(),
    "eigenextras" => array()
  );
}

/* Iterate over all eigens*/
foreach(array('eigentalks','eigenextras') as $type_of_eigen)
{
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
      // Get ID number
      // Row0: encoding
      // Row1: barcode
      // Row2: timestamp
      if (count($row)>1) 
      {
	$barcode = $row[1];
	// Eliminate leading zeroes and get rid of last two numbers
	$id = ltrim($barcode,'0');
	$id = substr($id,0,-2);
      } else 
      {
	$id = $row[0];
      }
      // Is the ID in the roster file?
      if (array_key_exists($id,$students))
      {
	// Is this scan already credited to the student
	if (!in_array($short_filename,$students[$id][$type_of_eigen]))
	{
	  array_push($students[$id][$type_of_eigen],$short_filename);
	}
      }
    }
  }
}
// Output to CSV
  echo "id,first name,last name,eigentalks,eigenextras,eigentotal,events\n";
  foreach($students as $student)
  {
    $events = '';
    foreach($student['eigentalks'] as $eigentalk)
      $events = $events . ' ' . $eigentalk;
    foreach($student['eigenextras'] as $eigenextra)
      $events = $events . ' ' . $eigenextra;
    $eigenextras = count($student['eigenextras']);
    $eigentalks = count($student['eigentalks']);
    $eigentotal = $eigenextras + $eigentalks;
    echo $student['id'] . ',' 
      . $student['first_name'] . ',' 
      . $student['last_name'] . ','
      . $eigentalks . ','
      . $eigenextras . ','
      . $eigentotal . ','
      . $events
      . "\n" ;
  }
?>
