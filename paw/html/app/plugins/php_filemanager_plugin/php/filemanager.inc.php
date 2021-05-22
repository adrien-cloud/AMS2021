<?
/*
 +-------------------------------------------------------------------+
 |                     F I L E M A N A G E R   (v4.4)                |
 |                                                                   |
 | Copyright Gerd Tentler               www.gerd-tentler.de/tools    |
 | Created: Dec. 7, 2006                Last modified: Sep. 21, 2008 |
 +-------------------------------------------------------------------+
 | This program may be used and hosted free of charge by anyone for  |
 | personal purpose as long as this copyright notice remains intact. |
 |                                                                   |
 | Obtain permission before selling the code for this program or     |
 | hosting this software on a commercial website or redistributing   |
 | this software over the Internet or in any other medium. In all    |
 | cases copyright must remain intact.                               |
 +-------------------------------------------------------------------+
*/
  error_reporting(E_WARNING);

//========================================================================================================
// Set variables, if they are not registered globally; needs PHP 4.1.0 or higher
//========================================================================================================

  if(isset($_REQUEST['fmMode'])) $fmMode = $_REQUEST['fmMode'];
  if(isset($_REQUEST['fmObject'])) $fmObject = $_REQUEST['fmObject'];
  if(isset($_REQUEST['fmSent'])) $fmSent = $_REQUEST['fmSent'];
  if(isset($_REQUEST['fmText'])) $fmText = $_REQUEST['fmText'];
  if(isset($_REQUEST['fmName'])) $fmName = $_REQUEST['fmName'];
  if(isset($_REQUEST['fmPerms'])) $fmPerms = $_REQUEST['fmPerms'];
  if(isset($_REQUEST['fmReplSpaces'])) $fmReplSpaces = $_REQUEST['fmReplSpaces'];
  if(isset($_REQUEST['fmLowerCase'])) $fmLowerCase = $_REQUEST['fmLowerCase'];
  if(isset($_REQUEST['fmView'])) $fmView = $_REQUEST['fmView'];

  if(isset($_FILES['fmFile'])) $fmFile = $_FILES['fmFile'];

  if(isset($_SERVER['PHP_SELF'])) $PHP_SELF = $_SERVER['PHP_SELF'];
  if(isset($_SERVER['HTTP_HOST'])) $HTTP_HOST = $_SERVER['HTTP_HOST'];

//========================================================================================================
// Get / set session variables; needs PHP 4 or higher
//========================================================================================================

  if(!isset($fmView)) $fmView = $_SESSION['fmView'];
  else $_SESSION['fmView'] = $fmView;

  $fmCurDir = $_SESSION['fmCurDir'];
  $fmListing = $_SESSION['fmListing'];
  $fmSysType = $_SESSION['fmSysType'];
  $fmSearch = $_SESSION['fmSearch'];
  $fmSortField = $_SESSION['fmSortField'];
  $fmSortOrder = $_SESSION['fmSortOrder'];

//========================================================================================================
// Initialize variables
//========================================================================================================

  $fmError = '';
  $fmPrevDir = '';

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
?>
<script language="JavaScript" src="<? echo $fmWebPath; ?>/filemanager.js"></script>
<link rel="stylesheet" href="<? echo $fmWebPath; ?>/filemanager.css" type="text/css">
<div id="fmListing" align="<? echo $fmAlign; ?>" style="margin:<? echo $fmMargin; ?>px">
<?
  if($ftp_server == '' && $startDir == '') {
    $fmError = "SECURITY ALERT:<br>Please set a start directory or an FTP server!";
  }
  else {
    if($ftp_server) $ftp = fm_connect();
    else $ftp = false;

    if(!$ftp_server || $ftp) {
      $startDir = str_replace('\\', '/', ($ftp ? $startDir : realpath($startDir)));
      if($ftp && $startDir == '.') $startDir = '';
      if($fmCurDir == '') $fmCurDir = $startDir;
      $fmPrevDir = $_SESSION['fmCurDir'] = $fmCurDir;

      switch($fmMode) {

        case 'edit':
          if($fmObject != '' && $fmListing[$fmObject]) {
            $path = dirname($fmListing[$fmObject]['path']);
            $name = $fmListing[$fmObject]['name'];
            include('edit.inc.php');
          }
          break;

        case 'rename':
          if($enableRename) {
            if($fmName != '' && $fmObject != '' && $fmListing[$fmObject]) {
              $path = dirname($fmListing[$fmObject]['path']);
              $name = $fmListing[$fmObject]['name'];
              if(!fm_rename("$path/$name", "$path/$fmName")) {
                $fmError = $msg['errRename'] . ": $name &raquo; $fmName";
              }
              else $fmListing = array();
            }
          }
          include('listing.inc.php');
          break;

        case 'permissions':
          if($enablePermissions) {
            if(is_array($fmPerms) && $fmObject != '' && $fmListing[$fmObject]) {
              $mode = '0';
              for($i = $cnt = 0; $i < 3; $i++) {
                for($j = $sum = 0; $j < 3; $j++) $sum += $fmPerms[$cnt++];
                $mode .= $sum;
              }
              $path = dirname($fmListing[$fmObject]['path']);
              $name = $fmListing[$fmObject]['name'];
              if(!fm_chmod("$path/$name", $mode)) {
                $fmError = $msg['errPermChange'] . ": $name";
              }
              else $fmListing = array();
            }
          }
          include('listing.inc.php');
          break;

        case 'newDir':
          if($enableNewDir) {
            if($fmName != '') {
              if(get_magic_quotes_gpc()) $fmName = stripslashes($fmName);
              $fmName = str_replace('\\', '/', $fmName);
              $dirs = explode('/', $fmName);
              $dir = '';

              for($i = 0; $i < count($dirs); $i++) {
                if($dirs[$i] != '') {
                  if($dir != '') $dir .= '/';
                  $dir .= $dirs[$i];

                  if(!fm_mkdir("$fmCurDir/$dir")) {
                    $fmError = $msg['errDirNew'] . ": $dir";
                    break;
                  }
                  else if($defaultDirPermissions) {
                    if(!fm_chmod("$fmCurDir/$dir", $defaultDirPermissions)) {
                      $fmError = $msg['errPermChange'] . ": $dir";
                      break;
                    }
                  }
                }
              }
              if(!$fmError) $fmListing = array();
            }
          }
          include('listing.inc.php');
          break;

        case 'newFile':
          if($enableUpload) {
            if(is_array($fmFile)) {
              for($i = 0; $i < count($fmFile['size']); $i++) {
                if($fmFile['size'][$i]) {
                  $newFile = $fmFile['name'][$i];

                  if($hideSystemFiles && $newFile[0] == '.') {
                    $fmError = $msg['errAccess'] . ": $newFile<br>";
                  }
                  else {
                    if($fmReplSpaces) $newFile = str_replace(' ', '_', $newFile);
                    if($fmLowerCase) $newFile = strtolower($newFile);
                    if(!fm_upload($fmFile['tmp_name'][$i], "$fmCurDir/$newFile")) {
                      $fmError .= $msg['errSave'] . ": $newFile<br>";
                    }
                    else {
                      $fmListing = array();
                      if($defaultFilePermissions) {
                        if(!fm_chmod("$fmCurDir/$newFile", $defaultFilePermissions)) {
                          $fmError .= $msg['errPermChange'] . ": $newFile<br>";
                        }
                      }
                    }
                  }
                }
                else if($fmFile['name'][$i] != '') {
                  $fmError .= $msg['error'] . ': ' . $fmFile['name'][$i] . ' = 0 B<br>';
                }
              }
            }
          }
          include('listing.inc.php');
          break;

        case 'removeFile':
          if($fmObject != '' && $fmListing[$fmObject] && $enableDelete) {
            $path = dirname($fmListing[$fmObject]['path']);
            $name = $fmListing[$fmObject]['name'];
            if(!fm_delete("$path/$name")) {
              $fmError = $msg['errDelete'] . ": $name";
            }
            else $fmListing = array();
          }
          include('listing.inc.php');
          break;

        case 'removeDir':
          if($fmObject != '' && $fmListing[$fmObject] && $enableDelete) {
            $path = $fmListing[$fmObject]['path'];
            $name = $fmListing[$fmObject]['name'];
            if(!fm_rmdir($path)) {
              $fmError = $msg['errDelete'] . ": $name";
            }
            else $fmListing = array();
          }
          include('listing.inc.php');
          break;

        case 'search':
          $fmSearch = $_SESSION['fmSearch'] = $fmName;
          $fmListing = array();
          include('listing.inc.php');
          break;

        case 'sort':
          list($fmSortField, $fmSortOrder) = explode(',', $fmName);
          $sort = true;
          include('listing.inc.php');
          break;

        case 'refresh':
          $fmListing = array();
          include('listing.inc.php');
          break;

        case 'open':
          if($fmObject != '' && $fmListing[$fmObject]['icon'] == 'dir') {
            $fmCurDir = $_SESSION['fmCurDir'] = $fmListing[$fmObject]['path'];
            $fmSearch = $_SESSION['fmSearch'] = '';
            $fmListing = array();
          }
          include('listing.inc.php');
          break;

        case 'parent':
          $fmCurDir = ereg_replace('/[^/]+$', '', $fmCurDir);
          if(substr($fmCurDir, 0, strlen($startDir)) == $startDir) {
            $_SESSION['fmCurDir'] = $fmCurDir;
            $fmListing = array();
          }
          include('listing.inc.php');
          break;

        default: include('listing.inc.php');
      }

      if($ftp) {
        @ftp_quit($ftp);

        $tmp = str_replace('\\', '/', dirname(__FILE__)) . '/tmp';
        if($dp = @opendir($tmp)) {
          while(($file = @readdir($dp)) !== false) {
            if($file != '.' && $file != '..') @unlink("$tmp/$file");
          }
          @closedir($tmp);
        }
      }
    }
  }
?>
</div>
<?
//========================================================================================================
// Dialog Boxes
//========================================================================================================
?>
<div id="fmInfo" class="fmDialog">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<td class="fmTH1" style="padding:4px" align="left"><? echo $msg['fileInfo']; ?></td>
<td class="fmTH1" style="padding:2px" align="right"><? echo fm_close_button(); ?></td>
</tr><tr>
<td class="fmTH1" colspan="2" style="padding:1px">
<div id="fmInfoText" class="fmTD2" style="padding:4px"></div></td>
</tr></table>
</div>

<div id="fmError" class="fmDialog">
<table border="0" cellspacing="0" cellpadding="0" width="400"><tr>
<td class="fmTH1" style="padding:4px" align="left"><? echo $msg['error']; ?></td>
<td class="fmTH1" style="padding:2px" align="right"><? echo fm_close_button(); ?></td>
</tr><tr>
<td class="fmTH3" colspan="2" style="padding:4px">
<div id="fmErrorText" class="fmError"></div></td>
</tr></table>
</div>

<div id="fmRename" class="fmDialog">
<form name="fmRename" class="fmForm" action="<? echo $PHP_SELF; ?>" method="post">
<input type="hidden" name="fmMode" value="rename">
<input type="hidden" name="fmObject" value="">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<td id="fmRenameText" class="fmTH1" style="padding:4px" align="left" nowrap></td>
<td class="fmTH1" style="padding:2px" align="right"><? echo fm_close_button(); ?></td>
</tr><tr>
<td class="fmTH3" colspan="2" align="center" style="padding:4px">
<input type="text" name="fmName" size="40" maxlength="60" class="fmField" value=""><br>
<input type="submit" class="fmButton" value="<? echo $msg['cmdRename']; ?>">
</td>
</tr></table>
</form>
</div>

<div id="fmPerm" class="fmDialog">
<form name="fmPerm" class="fmForm" action="<? echo $PHP_SELF; ?>" method="post">
<input type="hidden" name="fmMode" value="permissions">
<input type="hidden" name="fmObject" value="">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<td id="fmPermText" class="fmTH1" style="padding:4px" align="left" nowrap></td>
<td class="fmTH1" style="padding:2px" align="right"><? echo fm_close_button(); ?></td>
</tr><tr>
<td class="fmTH3" colspan="2" align="center" style="padding:4px">
<table border="0" cellspacing="2" cellpadding="4"><tr align="center">
<td class="fmTH2"><? echo $msg['owner']; ?></td>
<td class="fmTH2"><? echo $msg['group']; ?></td>
<td class="fmTH2"><? echo $msg['other']; ?></td>
</tr><tr align="left">
<?
  for($i = 0; $i < 9; $i += 3) {
?>
    <td class="fmTD2" nowrap>
    <input type="checkbox" name="fmPerms[<? echo $i; ?>]" value="4"> <? echo $msg['read']; ?><br>
    <input type="checkbox" name="fmPerms[<? echo $i+1; ?>]" value="2"> <? echo $msg['write']; ?><br>
    <input type="checkbox" name="fmPerms[<? echo $i+2; ?>]" value="1"> <? echo $msg['execute']; ?>
    </td>
<?
  }
?>
</tr></table>
<input type="submit" class="fmButton" value="<? echo $msg['cmdChangePerm']; ?>">
</td>
</tr></table>
</form>
</div>

<div id="fmNewFile" class="fmDialog">
<form name="fmNewFile" class="fmForm" action="<? echo $PHP_SELF; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="fmMode" value="newFile">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<td id="fmNewFileText" class="fmTH1" style="padding:4px" align="left" nowrap></td>
<td class="fmTH1" style="padding:2px" align="right"><? echo fm_close_button(); ?></td>
</tr><tr>
<td class="fmTH3" colspan="2" align="center" style="padding:4px">
<input type="file" name="fmFile[0]" size="20" class="fmField" onClick="fmNewFileSelector(1)" onChange="fmNewFileSelector(1)">
<input type="file" name="fmFile[1]" size="20" class="fmField" onClick="fmNewFileSelector(2)" onChange="fmNewFileSelector(2)" style="display:none">
<input type="file" name="fmFile[2]" size="20" class="fmField" onClick="fmNewFileSelector(3)" onChange="fmNewFileSelector(3)" style="display:none">
<input type="file" name="fmFile[3]" size="20" class="fmField" onClick="fmNewFileSelector(4)" onChange="fmNewFileSelector(4)" style="display:none">
<input type="file" name="fmFile[4]" size="20" class="fmField" onClick="fmNewFileSelector(5)" onChange="fmNewFileSelector(5)" style="display:none">
<input type="file" name="fmFile[5]" size="20" class="fmField" onClick="fmNewFileSelector(6)" onChange="fmNewFileSelector(6)" style="display:none">
<input type="file" name="fmFile[6]" size="20" class="fmField" onClick="fmNewFileSelector(7)" onChange="fmNewFileSelector(7)" style="display:none">
<input type="file" name="fmFile[7]" size="20" class="fmField" onClick="fmNewFileSelector(8)" onChange="fmNewFileSelector(8)" style="display:none">
<input type="file" name="fmFile[8]" size="20" class="fmField" onClick="fmNewFileSelector(9)" onChange="fmNewFileSelector(9)" style="display:none">
<input type="file" name="fmFile[9]" size="20" class="fmField" style="display:none">
<div class="fmTH3" style="font-weight:normal; text-align:left; border:none">
<input type="checkbox" name="fmReplSpaces" value="1"<? if($replSpacesUpload) echo ' checked'; ?>>
file name =&gt; file_name<br>
<input type="checkbox" name="fmLowerCase" value="1"<? if($lowerCaseUpload) echo ' checked'; ?>>
FileName =&gt; filename
</div>
<input type="submit" class="fmButton" value="<? echo $msg['cmdUploadFile']; ?>">
</td>
</tr></table>
</form>
</div>

<div id="fmNewDir" class="fmDialog">
<form name="fmNewDir" class="fmForm" action="<? echo $PHP_SELF; ?>" method="post">
<input type="hidden" name="fmMode" value="newDir">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<td id="fmNewDirText" class="fmTH1" style="padding:4px" align="left" nowrap></td>
<td class="fmTH1" style="padding:2px" align="right"><? echo fm_close_button(); ?></td>
</tr><tr>
<td class="fmTH3" colspan="2" align="center" style="padding:4px">
<input type="text" name="fmName" size="40" maxlength="60" class="fmField"><br>
<input type="submit" class="fmButton" value="<? echo $msg['cmdNewDir']; ?>">
</td>
</tr></table>
</form>
</div>

<div id="fmSearch" class="fmDialog">
<form name="fmSearch" class="fmForm" action="<? echo $PHP_SELF; ?>" method="post">
<input type="hidden" name="fmMode" value="search">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<td id="fmSearchText" class="fmTH1" style="padding:4px" align="left" nowrap></td>
<td class="fmTH1" style="padding:2px" align="right"><? echo fm_close_button(); ?></td>
</tr><tr>
<td class="fmTH3" colspan="2" align="center" style="padding:4px">
<input type="text" name="fmName" size="40" maxlength="60" class="fmField"><br>
<input type="submit" class="fmButton" value="<? echo $msg['cmdSearch']; ?>">
</td>
</tr></table>
</form>
</div>
<?
//========================================================================================================

  if($fmError) {
?>
    <script language="JavaScript"> <!--
    setTimeout('fmViewError("<? echo $fmError; ?>")', 500);
    //--> </script>
<?
  }
?>
