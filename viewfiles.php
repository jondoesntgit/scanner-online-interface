<h2>General Physics</h2>
<?php

$directory = "uploads/generalPhysics/";
$phpfiles = glob($directory . "*.csv");
foreach($phpfiles as $phpfile)
{
    echo '<a href="'.$directory.basename($phpfile).'">'.$phpfile.'</a>';
}
?>

<h2>Eigen</h2>
<?php
$directory = "uploads/eigen/";
$phpfiles = glob($directory . "*.csv");
foreach($phpfiles as $phpfile)
{
    echo '<a href="'.$directory.basename($phpfile).'">'.$phpfile.'</a>';
}
?>
