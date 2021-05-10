<?php
$current = file_get_contents('./points/current', FILE_USE_INCLUDE_PATH);
echo "<table class=\"ex1\" style=\"width:50%\"><tr class=\"ex1\"><td class=\"ex1\">";
echo "<a href=\"#\" style=\"width:100%\" class=\"myButton\">".$current."</a>";
echo "</td></tr></table>";
?>
