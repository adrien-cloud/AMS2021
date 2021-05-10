<?php
  $path=$_GET['path'];
  $myfile = fopen("points/play_me", "w");
  fwrite($myfile, $path);
  fclose($myfile);
  echo $path;
?>
