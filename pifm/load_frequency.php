<?php
  $value=$_GET['value'];
  $myfile = fopen("points/frequency", "w");
  fwrite($myfile, $value);
  fclose($myfile);
  echo $value;
?>
