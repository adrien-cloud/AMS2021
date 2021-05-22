<?
/*********************************************************************************************************
 This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
*********************************************************************************************************/
/*
 ARGUMENTS: width  = thumbnail width
            height = thumbnail height
            id     = file ID

 EXAMPLE:   <img src="thumbnail.php?width=200&height=100&id=12">
*/
  error_reporting(E_WARNING);

//========================================================================================================
// Start session and get session variables; needs PHP 4 or higher
//========================================================================================================

  if(function_exists('session_start')) session_start();

  $fmListing = $_SESSION['fmListing'];

//========================================================================================================
// Set variables, if they are not registered globally; needs PHP 4.1.0 or higher
//========================================================================================================

  if(isset($_REQUEST['width'])) $width = $_REQUEST['width'];
  if(isset($_REQUEST['height'])) $height = $_REQUEST['height'];
  if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];

//========================================================================================================
// Includes
//========================================================================================================

  if($HTTP_HOST == 'localhost' || $HTTP_HOST == '127.0.0.1' || ereg('^192\.168\.0\.[0-9]+$', $HTTP_HOST)) {
    include('config_local.inc.php');
  }
  else {
    include('config_main.inc.php');
  }
  include('fmlib.inc.php');

//========================================================================================================
// Functions
//========================================================================================================

  function viewImage($img = '') {
    global $file, $type;

    switch($type) {
      case 1:
        if($img && function_exists('ImageGIF')) {
          header('Content-type: image/gif');
          @ImageGIF($img);
        }
        else if($img && function_exists('ImagePNG')) {
          header('Content-type: image/png');
          @ImagePNG($img);
        }
        else {
          header('Content-type: image/gif');
          readfile($file);
        }
      break;

      case 2:
        header('Content-type: image/jpeg');
        if($img && function_exists('ImageJPEG')) @ImageJPEG($img);
        else readfile($file);
      break;

      case 3:
        header('Content-type: image/png');
        if($img && function_exists('ImagePNG')) @ImagePNG($img);
        else readfile($file);
      break;

      default:
        header('Content-type: image/gif');
        echo base64_decode('R0lGODlhCgAKAIAAAMDAwAAAACH5BAEAAAAALAAAAAAKAAoAAAIIhI+py+0PYysAOw==');
    }
  }

//========================================================================================================
// Main
//========================================================================================================

  header('Cache-control: private, no-cache, must-revalidate');
  header('Expires: 0');

  $file = fm_get_path($fmListing[$id]['path']);
  list($src_width, $src_height, $type) = @getimagesize($file);

  if($src_width > $width || $src_height > $height) {
    $src_img = '';

    switch($type) {
      case 1:
        if(function_exists('ImageCreateFromGIF')) {
          $src_img = @ImageCreateFromGIF($file);
        }
        break;

      case 2:
        if(function_exists('ImageCreateFromJPEG')) {
          $src_img = @ImageCreateFromJPEG($file);
        }
        break;

      case 3:
        if(function_exists('ImageCreateFromPNG')) {
          $src_img = @ImageCreateFromPNG($file);
        }
        break;
    }

    if($src_img) {
      if($type != 1 && function_exists('ImageCreateTrueColor')) {
        $dst_img = @ImageCreateTrueColor($width, $height);
      }
      else $dst_img = @ImageCreate($width, $height);

      if(function_exists('ImageCopyResampled')) {
        @ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $width, $height, $src_width, $src_height);
      }
      else @ImageCopyResized($dst_img, $src_img, 0, 0, 0, 0, $width, $height, $src_width, $src_height);

      viewImage($dst_img);

      ImageDestroy($src_img);
      ImageDestroy($dst_img);
    }
    else viewImage();
  }
  else viewImage();
?>
