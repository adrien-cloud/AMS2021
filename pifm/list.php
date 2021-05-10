<?php
$dir = 'playlist';
$files = scandir($dir);
echo "<table class=\"ex1\" style=\"width:50%\"><tr class=\"ex1\"><td class=\"ex1\">";
foreach ($files as &$value) {
  if($value !== "." && $value !== "..") {
    echo "<a  onclick=\"onClick('".$value."')\" href=\"#\" style=\"width:84.48602%\" class=\"myButton\">".$value."</a>\n";
  }
}
echo "</td></tr></table>";
?>
