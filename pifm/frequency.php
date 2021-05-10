<?php
$current = file_get_contents('./points/frequency', FILE_USE_INCLUDE_PATH);
echo "<form class=\"myButton3\" style=\"background-color:#7a7a7a\"><tr class=\"ex1\"><td class=\"ex1\">
<input id=\"freq\" type=\"text\" name=\"frequency\" value=\"".$current."\">
<input type=\"submit\" value=\"SET\" onclick=\"onClickF()\"></td></tr></form>";
?>
