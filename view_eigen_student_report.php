<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

</head>
<script>

$(document).ready(function(){
  reportGen();
})

function reportGen(){
var id = $("#idField").val();
//var url = "eigen_student_report.php?id=149306";
var url = "eigen_student_report.php?id=149306";
console.log(url);
url = "eigen_student_report.php?id=" + id;
console.log(url);
$.getJSON( url, function( data ) {
  var items = [];
  if (data['id'] % 1 === 0) { 
    if (data['eigentalks'].length > 0){
      items.push( '<p>Here is a list of eigentalks which you have attended' );
      items.push( '<ol>');
      $.each( data['eigentalks'], function(val) {
	items.push( "<li> " + data['eigentalks'][val]);
      });
      items.push( '</ol></p>');
    } else {
      items.push( '<p>You have not attended any eigenextras</p>' )
    }

    if (data['eigenextras'].length > 0){
      items.push( 'Here is a list of eigenextras which you have attended');
      items.push( '<ol>');
      $.each( data['eigenextras'], function(val) {
	items.push( "<li> " + data['eigenextras'][val]);
      });
      items.push( '</ol><br>');
    } else {
      items.push( '<p>You have not attended any eigenextras</p>' )
    }
  } else {
    items.push( '<p>The ID you entered is not a valid ID number</p>' )
  }

  $("#reportDiv").html(items.join(""));
  });
}
</script>
</head>

<body>

ID: <input type=text id=idField onKeyDown="Javascript: if (event.keyCode==13) reportGen()">
<input type=submit onclick=reportGen()>

<div id='reportDiv'>
  
</div>
</body>
