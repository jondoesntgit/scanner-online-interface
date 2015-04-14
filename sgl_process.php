<?php
require_once('extract_id.php');
/* Headers for downloading file */

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=sglreport.csv');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

//header('Content-Length: ' . filesize($file));

/* Get roster file */
if (isset($_POST['roster_file'])) {
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
    "checkpoints" => array(),
    "timestamps" => array(),
    "ec" => array()
  );
}

$ec = false;
$checkpoint = null;
/* Iterate over all passed files*/
if(isset($_POST['sgl_files']))
foreach($_POST['sgl_files'] as $sgl_file)
{
  // Set short filename for display
  $short_filename = $sgl_file;
  if (strpos($short_filename, '/') !== FALSE)
    $short_filename = substr($short_filename, strrpos($short_filename, '/') + 1);
  if (strpos($short_filename, '.') !== FALSE) 
    $short_filename = substr($short_filename, 0, strpos($short_filename, "."));
  $filename = 'uploads/generalphysics/' . $short_filename . '.csv';
  // Open file
  $contents = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $csvRows = array_map('str_getcsv', $contents);    
  // Give students credit that were in file
  foreach($csvRows as $row) 
  {
    $special_id_arr = array('ec','start 1','start 2','start 3', 'start 4', 'start 5', 'stop');
    $id = extract_id($row,$special_id_arr);
    // Is this a start, stop, ec, etc...
    if  (in_array($id,$special_id_arr)) {
      if(strcmp($id,'ec')==0) $ec = true;
      elseif(strcmp($id,'stop')==0) {$ec = false; $checkpoint = null;}
      else {
	$checkpoint = substr($id,-1);
      }
    }
    // Is this a barcode?
    elseif (is_numeric($id)) {
      // Is the ID in the roster file?
      if (array_key_exists($id,$students))
	// Is the checkpoint set?
	if (!is_null($checkpoint))
	  // Is this checkpoint already credited to the student
	  if (!in_array($checkpoint,$students[$id]['checkpoints']))
	  {
	    array_push($students[$id]['checkpoints'],$checkpoint);
	    //array_push($students[$id]['timestamps'],$row[2]); // TODO: Someday define timestamps in extract_timestamp method
	    if ($ec) array_push($students[$id]['checkpoints'],'+EC');
	  }
    }
    // This is not a start, stop, ec, it's something crazy...
    else 
    {
	    echo "Weird scan: ";
	   print_r($row); 
    }
  }
}
else
  die ('No sgl_files were passed');
// Output to CSV
  echo "id,first name,last name,score,checkpoints,timestamps\n";
  foreach($students as $student)
  {
    $checkpoints = '';
    foreach($student['checkpoints'] as $checkpoint)
    {
      $checkpoints = $checkpoints . ' ' . $checkpoint;
    }
    $timestamps = '';
    foreach($student['timestamps'] as $timestamp)
    {
      $timestamps = $timestamps . $timestamp . ',';
    }
    $total = count($student['checkpoints']);
    echo $student['id'] . ',' 
      . $student['first_name'] . ',' 
      . $student['last_name'] . ','
      . $total . ','
      . $checkpoints . ','
      . $timestamps
      . "\n" ;
  }
?>
