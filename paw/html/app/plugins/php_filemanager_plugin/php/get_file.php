<?
/*********************************************************************************************************
 This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
*********************************************************************************************************/

  error_reporting(E_WARNING);

//========================================================================================================
// Start session and get session variables; needs PHP 4 or higher
//========================================================================================================

  if(function_exists('session_start')) session_start();

  $fmListing = $_SESSION['fmListing'];

//========================================================================================================
// Set variables, if they are not registered globally; needs PHP 4.1.0 or higher
//========================================================================================================

  if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];

  if(isset($_SERVER['HTTP_HOST'])) $HTTP_HOST = $_SERVER['HTTP_HOST'];

//========================================================================================================
// Includes
//========================================================================================================

  if($HTTP_HOST == 'localhost' || $HTTP_HOST == '127.0.0.1' || ereg('^192\.168\.0\.[0-9]+$', $HTTP_HOST)) {
    include('config_local.inc.php');
  }
  else {
    include('config_main.inc.php');
  }

  if(!isset($language)) $language = 'en';
  include("languages/lang_$language.inc");
  include('fmlib.inc.php');

//========================================================================================================
// Main
//========================================================================================================

  if(is_array($fmListing) && $fmListing[$id]) {
    $info = $fmListing[$id];
    $filename = $info['name'];

    if($hideSystemFiles && $filename[0] == '.') {
      $error = $msg['errAccess'] . ": $filename";
    }
    else {
      if($ftp_server) {
        if($ftp = fm_connect()) {
          $file = fm_get($info['path']);
          @ftp_quit($ftp);
        }
      }
      else $file = $info['path'];

      if(is_file($file)) {
        if($replSpacesDownload) $filename = str_replace(' ', '_', $filename);
        if($lowerCaseDownload) $filename = strtolower($filename);

        header('Content-Type: application/octetstream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: private, no-cache, must-revalidate');
        header('Expires: 0');
        readfile($file);
      }
      else $error = $msg['errOpen'] . ": $filename";
    }
  }
  else $error = $msg['error'];

  if($error) {
?>
    <html>
    <head>
    <link rel="stylesheet" href="filemanager.css" type="text/css">
    </head>
    <body>
<?
    fm_view_error($error);
?>
    </body>
    </html>
<?
  }
?>
