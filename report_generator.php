<?     

// Declare the roster file
if (isset($_POST['roster_file'])) {
	$_POST['roster_file'];
} else {
	die('no roster file was given');
}

// Declare a list of files to be processed
// Do this in an array
if (isset($_POST['csv_files'])) {
	$csv_files = $_POST['csv_files'];
} else {
	die('no csv_files were passed');
}


// Create a dictionary of students based on roster file
//

// This is a variable the interpreter uses to 
// determine if extra credit needs to be granted
 $ec = false;




 ?>
