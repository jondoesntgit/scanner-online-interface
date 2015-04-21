<?php
function extract_id($string,$special_id_arr = null){
  if(is_array($string)) {
    // Change array to string
    $string=implode($string,',');
  }
  
  if ($special_id_arr != null){
    $lowstring = strtolower($string);
    foreach ($special_id_arr as $word)
      if (strpos($lowstring,$word) === false){
        // These are not the droids you're looking for
      } else {
         return($word);
      }
  }

  // As of 2015, 4 leading zeroes, 6 digit ID, 2 digit #of cards issued
  preg_match('/([1-9]\d{4,5})(?=\d{2})/',$string,$matches);
  if(sizeof($matches)>0)
  {
    $id = $matches[0];
    if (strlen($id)>6){
      $id = substr($id,0,-2); //Take off last two digits, they're the number of cards issued
      $id = ltrim($id,'0'); //Trim 0's from left
    }
    return($id);
  }
  // Find the ID in the text
  preg_match('/([1-9]\d{4,5}\d*)/',$string,$matches);
  if(sizeof($matches)>1) $id = $matches[0];
  else $id = 'No ID';
  return($id);
}
?>
