<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css" />
<title>Physics Scanner Hub</title>
<!-- Include jQuery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>


<script type="text/javascript">
<!--
    function toggle_visibility() {
       var selectElement = document.getElementById("selectInterface");
       divId = ($("#selectInterface").val())
       $('.togglableDisplay').not('#'+divId).hide()
       $('#'+divId).show()
    }
//-->
</script>

</head>
<body>

<div class="wrapper">
  <div class="main">

<select onchange=toggle_visibility() id="selectInterface">
  <option value="select_option">Select Option</option>
  <option value="upload_file">Upload Scanner CSV</option>
  <option value="eigen">Eigen Attendance Generator</option>
  <option value="list_files">List Files</option>
  <option value="sgl_report">SGL Report</option>
</select>

<hr>
    <div id="select_option" class="togglableDisplay">
<h1>
Welcome to the Physics' department Scanner Hub
</h1>

<p>
For installation details on the cs1504 scanner, view my github at <a href="http://github.com/wheelerj/cs1504">github.com/wheelerj/cs1504</a>
</p>

<p>
There are four interfaces:
</p>

<h2>Upload</h2>
<p>
In this interface, either the instructor the TA may upload files to the server. These files must be the files that the cs1504 scanner outputs. All the scripts rely on
<ol>
  <li>The first field being the scanning type (Code128 for AU ID's)
  <li>The second field being the ID number (usually 4 leading zeros, 6 digit ID, and 2 digits telling you how many ID cards you've had<br>
example: 000014930601 (Jonathan Wheeler's ID) - 0000 (placeholders - 149306 (AU ID) - 01 (This is my first ID card)
  <li>The final field is a timestamp recorded by the barcode reader
</ol>

<h3>Eigen</h3>
Regular eigentalks should be uploaded as eigentalks. All other events (eigenvespers)as eigenextras. The difference is that MATH389 only gives attendance for eigentalks, but not for eigenvespers, etc...<p>

<p>It is most useful to give your filename something descriptive. I usually name them something like:
<pre>
150206-eigentalk04-journal-club
</pre>
Where the 150206 represents 2015 Feb 6, and everything else just makes the file a bit more descriptive. This is useful because the report shows the instructor which eigenevents the student was at at a glance, and the list of events is generated from the filenames that student's ID is included in.

<h3>SGL</h3>
<p>It is most useful to give your filename something descriptive. I usually name them something like:
<pre>
150204-lab2c
</pre>
Where the 150204 represents 2015 Feb 4, and everything else just makes the file a bit more descriptive. For labs or SGLs where multiple scanners are used, the a, b, c, at the end of the filename help the server keep track of which file gets what, etc...


<h3>Manual changes</h3>
<p>
Manual changes may be uploaded, but care should be taken that the comma separated fields are left intact, or else php will hiccup. Error handling is a pain to code,
and to save time I have glazed over some of the errors that are created when one messes with manually adding ID's to a scan.

<p>
If you do find yourself needing to manually alter a file, type the following: Let's say Jonathan has missed an eigen: append
<pre>
,000014930601,
</pre>
to the end of the csv before uploading. The commas at the beginning and end will simply create '' for the encoding and '' for the timestamp, but should not cause 
php to complain about errors.

<p>
For 5-digit ID's, do
<pre>
,000009876502,
</pre>

<p>In most cases, as long as you have exactly 2 numbers after the ID, and any number of leading 0's, one should be fine. The script checks for 0's before and 2 characters after. The above code is synonymous with the following:
<pre>
,000000000000000009876501,
,09876599,
</pre>

<p>
It is important to note that because of permissions on the server, altering files after uploads can be a bit of a chore, so be careful with your uploads.

<h3>
Roster uploads
</h3>
An instructor may also load a class roster to the server. On vault, ask for a .csv file (not a pdf) to be uploaded. This file can be directly uploaded to the server without modification.

<h2>Eigen Attendance</h2>
<p>
Select which roster you wish to create a report for, and click the Generate Report button. Your browser should download the report csv, which can be opened up in Excel.

<h2>List Files</h2>
<p>
This is a list of all files on the server. This is helpful for checking to see if a file uploaded successfully.

<p>
If the list of files is becoming too daunting, contact Jonathan Wheeler to clear it.

<h2>SGL Report</h2>
<p>
Select which roster you wish to create a report for (General Physics), and then select all applicable csv files that are on the server. The program will trace through each
checked-off file and tally up which students completed what.

<p>
Note that sometimes the checkpoints may be out of order. This is because one csv file is evaluated completely before the other is begun. If a student did checkpoints 2 and 3
one one scanner, but checkpoint 1 on another, the checkpoints will appear out of order. For this reason the timestamps have been included in the downloaded csv file.

<p>
The program will go line-by-line through each CSV file. It starts giving credit whenever it hits a "Start #" scan, and then it will give all ID card scans credit for that checkpoint until it hits a stop codon. Whenever it hits an "EC" it turns an "EC" flag on, which will additionally give students a "+EC" marker in the csv downloaded file in addition to incrementing their total. It remains on until it hits a "Stop" codon. The "EC" flag will not be reset if it hits another "Start #" codon--it will merrily continue to give students extra credit"

<p>
It is not neccessary to scan a "Stop" after every group of students. Merely scanning the next "Start" codon is sufficient to change the checkpoint number for the next group. Stop is really only necessary when clearing "EC" although it will also clear the checkpoint number.

<p>To download a sheet of paper which contains all codons, click <a href="barcodes.pdf">here</a>.

<p>
If you experience any difficulties, please feel free to contact me at wheelerj@andrews.edu
</p>

    </div> <!--/ select option -->
    
    <div id="upload_file" class="togglableDisplay">
     <form action="upload_file.php" method="post" enctype="multipart/form-data">
       <p>
        Select the file to upload
       </p>
       <table>
        <tr><td>Filename:</td><td>	<input type="text" name="filename" id="filename"></td></tr>
        <tr><td>Purpose</td><td>	
         <select name="purpose">
          <option value="generalphysics">General Physics</option>
	  <option value="eigentalks">eigentalks</option>
	  <option value="eigenextras">eigenextras</option>
	  <option value="rosters">roster</option>
	 </select></td></tr>
         <tr><td>        </td></tr>
        </table>
       <input type="file" name="fileToUpload" id="fileToUpload"><br/>
       <input type="submit" value="Upload CSV File" name="submit">
    </form>
  </div> <!-- /upload_file -->


  <div id="eigen" class="togglableDisplay">
   <form action="eigen_process.php" method="post">
    <select name="roster_file">
<?php
$files = glob('uploads/rosters/*.csv',GLOB_BRACE);
foreach($files as $file) {
$short_filename = $file;
if (strpos($short_filename, '/') !== FALSE)
  $short_filename = substr($short_filename, strrpos($short_filename, '/') + 1);
if (strpos($short_filename, '.') !== FALSE) 
 $short_filename = substr($short_filename, 0, strpos($short_filename, "."));
echo '        <option value="'. $short_filename .'">' . $short_filename . '</option>' . "\n";
}
 // Populate rosters
?>
    </select>
    <input type="submit" value="Generate Report" name="submit">
   </form> <!-- /report_generator -->
  </div> <!-- /generator -->
  
  <div>

   </div>
  
<div id="list_files" class="togglableDisplay">
<ul>
<?php
function strip_slash($string) {
  if (strpos($string, '/') !== FALSE)
    $string = substr($string,strrpos($string, '/') + 1);
  return $string;
}
  foreach(glob('uploads/*', GLOB_ONLYDIR) as $dir)
  {
    echo '<li>' . strip_slash($dir);
    echo '<ul>';
    foreach(glob( $dir . '/*') as $file)
    {
      echo '<li><a href="' . $file . '">' . strip_slash($file) . '</a>';
    }
    echo '</ul><br><br>';
	   
  }
?>
</ul>
</div> <!-- /list files -->

<div class="togglableDisplay" id="sgl-report">
   <form action="sgl_process.php" method="post">
Select Roster File:<br>
    <select name="roster_file">
    <option>Select One</option>
<?php
$files = glob('uploads/rosters/*.csv',GLOB_BRACE);
foreach($files as $file) {
$short_filename = $file;
if (strpos($short_filename, '/') !== FALSE)
  $short_filename = substr($short_filename, strrpos($short_filename, '/') + 1);
if (strpos($short_filename, '.') !== FALSE) 
 $short_filename = substr($short_filename, 0, strpos($short_filename, "."));
echo '        <option value="'. $short_filename .'">' . $short_filename . '</option>' . "\n";
}
 // Populate rosters
?>
    </select>
<br>
<br>
Select Files<br>
<?php
  foreach(glob('uploads/generalphysics/*') as $file)
  {
    echo '<input type="checkbox" name="sgl_files[]" value="' . $file . '"><a href="' . $file . '">' . strip_slash($file) . '</a><br>';
  }
?>
<br>
    <input type="submit" value="Generate Report" name="submit">
   </form> <!-- /report_generator -->
</div>


</body>
<script type="text/javascript">
<!--
function getUrlParameter(sParam)
{
  var sPageURL = window.location.search.substring(1);
  var sURLVariables = sPageURL.split('&');
  for (var i = 0; i < sURLVariables.length; i++) 
  {
    var sParameterName = sURLVariables[i].split('=');
    if (sParameterName[0] == sParam) 
    {
    return sParameterName[1];
    }
  }
}     
if (getUrlParameter('show')==='files')
  $("#selectInterface").val('list_files').change()

divId = ($("#selectInterface").val())
$('.togglableDisplay').not('#'+divId).hide()
$('#'+divId).show()
//-->
</script>
</html>
