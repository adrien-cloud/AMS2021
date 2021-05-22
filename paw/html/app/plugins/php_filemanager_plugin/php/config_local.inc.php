<?
/*********************************************************************************************************
 This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
*********************************************************************************************************/

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// This is the optional configuration file for OFFLINE TESTING
//////////////////////////////////////////////////////////////////////////////////////////////////////////

  // FTP access; leave empty to use local file system instead
  $ftp_server = "";     // FTP server name, e.g. www.yourdomain.com
  $ftp_user = "";       // FTP user name
  $ftp_pass = "";       // FTP password

  // image preview: These variables are only used if FileManager is in FTP mode (i.e. you set the above
  // variables); they are not required if you use the local file system.
  // NOTE: Image preview will also work if you leave these variables empty, but it will take much more
  //       time because FileManager then must load all images from your FTP server every time a
  //       directory with images is opened, so I strongly recommend to set these variables if you use
  //       FileManager in FTP mode to speed things up.
  $ftp_webPath = "";    // FTP web path (URL), e.g. http://www.yourdomain.com
  $ftp_webDir = "";     // FTP web directory, e.g. 'public_html', 'username/htdocs'
                        // (leave empty if your FTP account starts in your web directory)

  // language: de, en, es, et, fi, fr, it, nl, pl, pt, pt-BR, ro, ru, sk, sv
  $language = "en";

  // start directory (file path, e.g. /home/users/gerry/htdocs/tools)
  // If not in FTP mode, PHP must have at least read permission for this directory!
  //if(!$startDir) $startDir = "../../..";
  if(!$startDir) $startDir = "/sdcard";

  // FileManager WEB path (e.g. /webtools/filemanager)
  // This should be the place where you installed FileManager
  $fmWebPath = ".";

  // FileManager width (pixels)
  $fmWidth = 600;

  // FileManager align (left / center / right)
  $fmAlign = "center";

  // FileManager margin (pixels)
  $fmMargin = 20;

  // FileManager default view ("details" or "icons")
  if(!isset($fmView)) $fmView = "details";

  // edit mask height (pixels)
  $maskHeight = 450;

  // max. width of preview thumbnails (pixels)
  $thumbMaxWidth = 200;

  // max. height of preview thumbnails (pixels)
  $thumbMaxHeight = 200;

  // default permissions for uploaded files (octal number, e.g. 0755)
  $defaultFilePermissions = 0755;

  // default permissions for new directories (octal number, e.g. 0755)
  $defaultDirPermissions = 0755;

  // hide system files with leading dot, e.g. .htaccess (true = yes, false = no)
  $hideSystemFiles = false;

  // enable file upload (true = yes, false = no)
  $enableUpload = true;

  // enable file download (true = yes, false = no)
  $enableDownload = true;

  // enable file editing (true = yes, false = no)
  $enableEdit = true;

  // enable file / directory deleting (true = yes, false = no)
  $enableDelete = true;

  // enable file / directory renaming (true = yes, false = no)
  $enableRename = true;

  // enable file / directory permissions changing (true = yes, false = no)
  $enablePermissions = true;

  // enable directory creation (true = yes, false = no)
  $enableNewDir = true;

  // upload: replace spaces in filenames with underscores (true = yes, false = no)
  $replSpacesUpload = false;

  // download: replace spaces in filenames with underscores (true = yes, false = no)
  $replSpacesDownload = false;

  // upload: convert filenames to lowercase (true = yes, false = no)
  $lowerCaseUpload = false;

  // download: convert filenames to lowercase (true = yes, false = no)
  $lowerCaseDownload = false;

//========================================================================================================
?>
