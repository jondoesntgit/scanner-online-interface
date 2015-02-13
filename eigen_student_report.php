<?php
// This file returns a JSON Object with student's report for Eigen
header('Content-Type: application/json');

// Check if GET parameter is set
if (isset($_GET['id']))
{
  $studentid = $_GET['id'];
}
else
  die ('ID parameter is not set');
  
// Clean parameter, make sure it is 6 digit number or die
preg_match('/(\d{6})/',$studentid,$matches);
if (sizeof($matches) == 0)
  preg_match('/(\d{5})/',$studentid,$matches);
if (sizeof($matches) == 0)
  $studentid = "Unparsable ID " . $_GET['id'];
else $studentid = $matches[0];

// Initialize array
$eigentalks = array();
$eigenextras = array();
// Get glob of directories
// Loop through files to find student
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
      // Is this the student's ID?
      if (strcmp($id,$studentid)==0)
      {
	// Is this scan already credited to the student
	if (!in_array($short_filename,$$type_of_eigen))
	{
	  array_push($$type_of_eigen,$short_filename);
	}
      }
    }
  }
}
// Append to array
// Finish loop
// Output JSON
$ret_array = array(
	"id" => $studentid,
	"eigentalks" => $eigentalks,
	"eigenextras" => $eigenextras);
echo json_encode($ret_array);
?>
