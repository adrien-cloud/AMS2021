<?php
try {
  $userAgent = $_SERVER['HTTP_USER_AGENT'];
  $userAgent = substr($userAgent, 0, 255);
  $remoteAddr = $_SERVER['REMOTE_ADDR'];
  $sessionId = $_COOKIE['cookie'];

  $sql =  "SELECT * FROM auth WHERE sessionid='" . $sessionId . "' AND remoteaddress='" . $remoteAddr . "' AND useragent='" . $userAgent . "' AND timestamp>=" . (microtime(true) - 24 * 3600 * 1000); 


  $db = new PDO('sqlite:/data/data/de.fun2code.android.pawserver/databases/authDB');
  $result = $db->query($sql);

  $count = 0;
  foreach($result as $row) { $count++; }

  if($count == 0) {
    header("Location: " . $_SERVER['SERVER_URL'] . "/app/login.xhtml");
    exit();
  }

}
catch(Exception $e)
{
  header("Location: " . $_SERVER['SERVER_URL'] . "/app/login.xhtml");
  exit();
}
?>
