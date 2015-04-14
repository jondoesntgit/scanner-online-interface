<?php
// This file is not used in the current setup, but may be useful in future commits

function get_scanner_type($testRow) {
  //Try CS1504
  preg_match('/(\d{5})/',$testRow[1],$matches); // Check to see if there are 5 numbers in a row
  if (sizeof($matches) == 1)
  return('CS1504');

  //Try CS3000
  preg_match('/(\d{5})/',$testRow[3],$matches); // Check to see if there are 5 numbers in a row
  if (sizeof($matches) == 1)
  return('CS3000');

  //Try Manual
  preg_match('/(\d{5})/',$testRow[0],$matches);
  if (sizeof($matches) == 1)
  return('MANUAL');

  return('Unknown')
}
?>
