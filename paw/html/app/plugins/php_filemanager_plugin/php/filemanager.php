<?php
include 'auth.php';
?>

<?
/*********************************************************************************************************
 This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
*********************************************************************************************************/

  if(function_exists('session_start')) session_start();

  header('Cache-control: private, no-cache, must-revalidate');
  header('Expires: 0');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>File Manager</title>
</head>
<body style="background-color:#F0F0F0">
<table border="0" width="100%" height="90%"><tr>
<td>
<?
  include('filemanager.inc.php');
?>
</td>
</tr></table>
</body>
</html>
