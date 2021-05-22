<?
/*********************************************************************************************************
 This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
*********************************************************************************************************/

//========================================================================================================
// Global Functions
//========================================================================================================

  if(!function_exists('ftp_chmod')) {
    function ftp_chmod($ftp, $mode, $file) {
      return @ftp_site($ftp, sprintf('CHMOD %s %s', $mode, $file));
    }
  }

  function fm_connect($dir = '') {
    global $msg, $fmError, $ftp_server, $ftp_user, $ftp_pass;

    if($ftp = @ftp_connect($ftp_server)) {
      if(@ftp_login($ftp, $ftp_user, $ftp_pass)) {
        if($dir) if(!@ftp_chdir($ftp, $dir)) $fmError = $msg['errOpen'];
      }
      else $fmError = $msg['errLogin'];
    }
    else $fmError = $msg['errConnect'];

    return $ftp;
  }

  function fm_get($file) {
    global $ftp;

    if($ftp) {
      $filename = basename($file);
      $tmp = str_replace('\\', '/', dirname(__FILE__)) . '/tmp';
      if(@ftp_get($ftp, "$tmp/$filename", $file, FTP_BINARY)) $file = "$tmp/$filename";
      else $file = '';
    }
    return $file;
  }

  function fm_rename($src, $dst) {
    global $ftp;
    if($ftp) return @ftp_rename($ftp, $src, $dst);
    else return @rename($src, $dst);
  }

  function fm_delete($file) {
    global $ftp;
    if($ftp) return @ftp_delete($ftp, $file);
    else return @unlink($file);
  }

  function fm_upload($src, $dst) {
    global $ftp;
    if($ftp) return @ftp_put($ftp, $dst, $src, FTP_BINARY);
    else return @move_uploaded_file($src, $dst);
  }

  function fm_rmdir($dir) {
    global $ftp;
    if($ftp) return @ftp_rmdir($ftp, $dir);
    else return @rmdir($dir);
  }

  function fm_mkdir($dir) {
    global $ftp;
    if($ftp) return @ftp_mkdir($ftp, $dir);
    else return @mkdir($dir, 0755);
  }

  function fm_chmod($file, $mode) {
    global $ftp;
    if($ftp) return @ftp_chmod($ftp, $mode, $file);
    else return @chmod($file, $mode);
  }

  function fm_get_perms($file) {
    if(is_dir($file)) {
      $perms = 'd';
      $rwx = substr(decoct(@fileperms($file)), 2);
    }
    else {
      $perms = '-';
      $rwx = substr(decoct(@fileperms($file)), 3);
    }
    for($i = 0; $i < strlen($rwx); $i++) {
      switch($rwx[$i]) {
        case 1: $perms .= '--x'; break;
        case 2: $perms .= '-w-'; break;
        case 3: $perms .= '-wx'; break;
        case 4: $perms .= 'r--'; break;
        case 5: $perms .= 'r-x'; break;
        case 6: $perms .= 'rw-'; break;
        case 7: $perms .= 'rwx'; break;
        default: $perms .= '---'; break;
      }
    }
    return $perms;
  }

  function fm_is_type($ext, $types) {
    while(list(,$val) = each($types)) {
      if(eregi($val, $ext)) return true;
    }
    return false;
  }

  function fm_sort_field($arr, $field, $sort = 'asc') {
    $cnt = count($arr);
    $swap = true;

    while($cnt && $swap) {
      $swap = false;
      for($i = 0; $i < $cnt; $i++) {
        for($j = $i; $j < $cnt-1; $j++) {
          if(($sort == 'asc' && $arr[$j][$field] > $arr[$j+1][$field]) ||
             ($sort == 'desc' && $arr[$j][$field] < $arr[$j+1][$field])) {
            $temp = $arr[$j];
            $arr[$j] = $arr[$j+1];
            $arr[$j+1] = $temp;
            $swap = true;
          }
        }
      }
      $cnt--;
    }
    return $arr;
  }

  function fm_get_path($file) {
    global $ftp_server, $ftp_webPath, $ftp_webDir, $fmListing;

    if($ftp_server) {
      if($ftp_webPath) {
        $path = $ftp_webPath;
        if(!$ftp_webDir) $path .= '/' . $file;
        else $path .= ereg_replace("^$ftp_webDir", '/', $file);
      }
      else if($ftp = fm_connect()) {
        $path = fm_get($file);
        @ftp_quit($ftp);
      }
      return $path;
    }
    return $file;
  }

  function fm_create_file_info($file, $dir, $systype = '', $name = '') {
    global $hideSystemFiles;

    $info = array();

    if($systype) {
      $systype = (stristr($systype, 'winnt') || stristr($systype, 'windows')) ? 'Windows' : 'UNIX';

      if($systype == 'UNIX') {
        if(preg_match('/^([drwx\-]{10}) +[0-9]+ +([^ ]+) +([^ ]+) +([0-9]+) +([a-zA-Zäöü]{3} +[0-9]+ +([0-9]{2,4} )?[0-9:]{4,5}) +(.+)$/', $file, $m)) {
          if($m[7] != '..') {
            if($name == '' || stristr($m[7], $name)) {
              $info['permissions'] = $m[1];
              $info['owner'] = $m[2];
              $info['group'] = $m[3];
              $info['size'] = $m[4];
              $info['changed'] = $m[6] ? date('Y-m-d H:i', strtotime($m[5])) : $m[5];
              $info['name'] = $m[7];
              $info['icon'] = ($info['permissions'][0] == 'd') ? 'dir' : '';
              $info['path'] = $dir . '/' . $info['name'];
            }
            else if($name != '' && $m[1][0] == 'd') $info = $m[7];
          }
        }
      }
      else if($systype == 'Windows') {
        if(preg_match('/^([0-9\.]{10}) +([0-9:]{5}) +(<DIR>)? +([0-9\.]*) +(.+)$/', $file, $m)) {
          if($m[5] != '..') {
            if($name == '' || stristr($m[5], $name)) {
              $d = explode('.', $m[1]);
              $t = explode(':', $m[2]);
              $tstamp = mktime($t[0], $t[1], 0, $d[1], $d[0], $d[2]);
              $info['changed'] = $tstamp ? date('Y-m-d H:i', $tstamp) : $m[1] . ' ' . $m[2];
              $info['permissions'] = $m[3];
              $info['size'] = str_replace('.', '', $m[4]);
              $info['name'] = $m[5];
              $info['icon'] = ($info['permissions'] == '<DIR>') ? 'dir' : '';
              $info['path'] = $dir . '/' . $info['name'];
            }
            else if($name != '' && $m[3] == '<DIR>') $info = $m[5];
          }
        }
      }
    }
    else {
      $filename = basename($file);
      if($filename != '.' && $filename != '..') {
        if($name == '' || stristr($filename, $name)) {
          $info['permissions'] = fm_get_perms($file);
          $info['owner'] = @fileowner($file);
          $info['group'] = @filegroup($file);
          $info['size'] = @filesize($file);
          $info['changed'] = date('Y-m-d H:i', @filemtime($file));
          $info['name'] = $filename;
          $info['icon'] = is_dir($file) ? 'dir' : '';
          $info['path'] = $dir . '/' . $info['name'];
        }
        else if($name != '' && is_dir($file)) $info = $file;
      }
    }
    return ($hideSystemFiles && $info['name'][0] == '.') ? false : $info;
  }

  function fm_list($dir, $search = '') {
    global $ftp, $fmListing;

    if($ftp) {
      $systype = @ftp_systype($ftp);
      $list = @ftp_rawlist($ftp, $dir);
      if(!is_array($list)) return false;
    }
    else if($dp = @opendir($dir)) {
      $list = array();
      $systype = '';

      while(($file = readdir($dp)) !== false) {
        $list[] = "$dir/$file";
      }
      @closedir($dp);
    }
    else return false;

    if(is_array($list)) foreach($list as $row) {
      $info = fm_get_info($row, $dir, $systype, $search);

      if(is_array($info) && count($info)) {
        $fmListing[] = $info;

        if($search != '' && $info['icon'] == 'dir') {
          fm_list($info['path'], $search);
        }
      }
      else if($info) fm_list($info, $search);
    }
    return true;
  }

  function fm_thumb_size($file, $maxWidth = 0, $maxHeight = 0) {
    $file = fm_get_path($file);
    list($width, $height, $type) = @getimagesize($file);

    if($type == 1 || $type == 2 || $type == 3) {
      if($maxWidth && $width > $maxWidth) {
        $perc = $maxWidth / $width;
        $width = round($width * $perc);
        $height = round($height * $perc);
      }
      if($maxHeight && $height > $maxHeight) {
        $perc = $maxHeight / $height;
        $width = round($width * $perc);
        $height = round($height * $perc);
      }
    }
    return array($width, $height, $type);
  }

  function fm_get_info($file, $dir = '', $systype = '', $name = '') {
    global $thumbMaxWidth, $thumbMaxHeight;

    $textfiles = array('txt', '[sp]?html?', 'css', 'jse?', 'php[0-9]*', 'pr?l', 'pm', 'cgi', 'inc', 'csv', 'py', 'asp', 'ini');
    $imagefiles = array('gif', 'jpe?g', 'png', 'w?bmp', 'tiff?', 'pict?', 'ico');
    $archivefiles = array('zip', '[rtj]ar', 't?gz', 't?bz2?', 'arj', 'ace', 'lzh', 'lha', 'xxe', 'uue?', 'iso', 'cab', 'r[0-9]+');
    $exefiles = array('exe', 'com', 'pif', 'bat', 'scr');
    $acrobatfiles = array('pd[fx]');
    $wordfiles = array('do[ct]', 'do[ct]html');
    $excelfiles = array('xl[stwv]', 'xl[st]html', 'slk');

    $info = fm_create_file_info($file, $dir, $systype, $name);

    if(is_array($info) && count($info)) {
      $info['thumb'] = $info['width'] = $info['height'] = 0;

      if(!$info['icon']) {
        $ext = substr($info['name'], strrpos($info['name'], '.') + 1);
        if(fm_is_type($ext, $textfiles)) $info['icon'] = 'text';
        else if(fm_is_type($ext, $imagefiles)) $info['icon'] = 'image';
        else if(fm_is_type($ext, $archivefiles)) $info['icon'] = 'archive';
        else if(fm_is_type($ext, $exefiles)) $info['icon'] = 'exe';
        else if(fm_is_type($ext, $acrobatfiles)) $info['icon'] = 'acrobat';
        else if(fm_is_type($ext, $wordfiles)) $info['icon'] = 'word';
        else if(fm_is_type($ext, $excelfiles)) $info['icon'] = 'excel';
        else $info['icon'] = 'file';

        if($info['icon'] == 'image') {
         list($width, $height, $type) = fm_thumb_size($info['path'], $thumbMaxWidth, $thumbMaxHeight);

          if($type == 1 || $type == 2 || $type == 3) {
            $info['thumb'] = md5($info['path']);
            $info['width'] = $width;
            $info['height'] = $height;
          }
        }
      }
    }
    return $info;
  }

  function fm_view_error($msg) {
    global $fmWidth;
    echo '<div class="fmError" style="width:' . $fmWidth . 'px">' . $msg . '</div>';
  }

  function fm_close_button() {
    return '<table border="0" cellspacing="0" cellpadding="0" width="16" height="16"><tr>' .
           '<td class="fmTH3" align="center"' .
           ' onMouseOver="this.className=\'fmTH4\'"' .
           ' onMouseOut="this.className=\'fmTH3\'"' .
           ' onMouseDown="this.className=\'fmTH5\'"' .
           ' onMouseUp="this.className=\'fmTH4\'"' .
           ' onClick="fmFadeOut()">&times;</td>' .
           '</tr></table>';
  }

//========================================================================================================
?>
