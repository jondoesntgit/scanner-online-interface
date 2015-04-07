<?php
function extract_id($string){
  if(is_array($string)) {
    // Change array to string
    $string=implode($string,',');
  }

  // As of 2015, 4 leading zeroes, 6 digit ID, 2 digit #of cards issued
  preg_match('/(0{3}\d{9,})/',$string,$matches);
  if(sizeof($matches)>1)
  {
    $id = $matches[0];
    if (strlen($id)>6){
      $id = substr($id,0,-2); //Take off last two digits, they're the number of cards issued
      $id = ltrim($id,'0'); //Trim 0's from left
    }
    return($id);
  }
  // Find the ID in the text
  preg_match('/(\d{5,6}\d*)/',$string,$matches);
  if(sizeof($matches)>1) $id = $matches[0];
  else $id = 'No ID';
  return($id);
}
?>
