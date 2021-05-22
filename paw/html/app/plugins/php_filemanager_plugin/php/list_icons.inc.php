<?
/*********************************************************************************************************
 This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
*********************************************************************************************************/

  $cellCnt = floor($fmWidth / 100);
  $contWidth = floor($fmWidth / $cellCnt) - 12;
  $maxNameLen = round($contWidth / 10);

  $cnt = 0;
?>
<table border="0" cellspacing="2" cellpadding="5"><tr align="center" valign="top">
<?
  if($fmSearch != '') {
?>
    <td class="<? echo $class; ?>" width="<? echo $contWidth; ?>" onMouseOver="this.className='fmTD2'" onMouseOut="this.className='<? echo $class; ?>'">
    <table border="0" cellspacing="0" cellpadding="0" style="width:100%; height:60px"><tr>
    <td align="center"
     style="cursor:pointer"
     title="<? echo $msg['cmdGoBack']; ?>"
     onClick="fmGoTo('<? echo "$PHP_SELF?fmMode=search"; ?>')"
     onMouseOver="window.status='<? echo $msg['cmdGoBack']; ?>'; return true"
     onMouseOut="window.status=''">
    <img src="<? echo $icons; ?>/cdup.gif" border="0" width="24" height="20"
     alt="<? echo $msg['cmdGoBack']; ?>" style="margin-top:20px">
    </td>
    </tr></table>
    </td>
<?
    $cnt++;
  }
  else if(strlen($fmCurDir) > strlen($startDir)) {
?>
    <td class="<? echo $class; ?>" width="<? echo $contWidth; ?>" onMouseOver="this.className='fmTD2'" onMouseOut="this.className='<? echo $class; ?>'">
    <table border="0" cellspacing="0" cellpadding="0" style="width:100%; height:60px"><tr>
    <td align="center"
     style="cursor:pointer"
     title="<? echo $msg['cmdParentDir']; ?>"
     onClick="fmGoTo('<? echo "$PHP_SELF?fmMode=parent"; ?>')"
     onMouseOver="window.status='<? echo $msg['cmdParentDir']; ?>'; return true"
     onMouseOut="window.status=''">
    <img src="<? echo $icons; ?>/cdup.gif" border="0" width="24" height="20"
     alt="<? echo $msg['cmdParentDir']; ?>" style="margin-top:20px">
    </td>
    </tr></table>
    ..
    </td>
<?
    $cnt++;
  }

  if(is_array($fmListing)) foreach($fmListing as $id => $val) {
    if($cnt >= $cellCnt) {
      echo "</tr><tr align=\"center\">\n";
      $cnt = 0;
    }
    $file = $val['name'];
    if(strlen($file) > $maxNameLen) $name = substr($file, 0, $maxNameLen) . '...';
    else $name = $file;
?>
    <td class="<? echo $class; ?>" width="<? echo $contWidth; ?>" onMouseOver="this.className='fmTD2'" onMouseOut="this.className='<? echo $class; ?>'">
<?
    if($val['icon'] == 'dir') {
      $tooltip = $msg['cmdChangeDir'];
      $url = "fmGoTo('$PHP_SELF?fmMode=open&fmObject=$id')";
    }
    else if($enableDownload) {
      $tooltip = $msg['cmdGetFile'];
      $url = "fmGoTo('$fmWebPath/get_file.php?id=$id')";
    }
    else $tooltip = $url = '';
?>
    <table border="0" cellspacing="0" cellpadding="0" style="width:100%; height:60px"><tr>
    <td align="center"
     style="cursor:<? echo ($url ? 'pointer' : 'default'); if($val['thumb']) echo '; border:1px solid #E0E0E0'; ?>"
     title="<? echo $tooltip; ?>"
     onClick="<? echo $url; ?>"
     onMouseOver="window.status='<? echo $tooltip; ?>'; return true"
     onMouseOut="window.status=''">
<?
    if($val['thumb']) {
      list($width, $height) = fm_thumb_size($val['path'], $contWidth - 10, 60);
?>
      <img src="<? echo "$fmWebPath/thumbnail.php?width=$width&height=$height&id=$id&" . $val['thumb']; ?>" border="0"
       width="<? echo $width; ?>" height="<? echo $height; ?>" alt="<? echo $tooltip; ?>">
<?
    }
    else {
?>
      <img src="<? echo "$icons/" . $val['icon']; ?>_big.gif" border="0" width="32" height="32" alt="<? echo $tooltip; ?>">
<?
    }
?>
    </td>
    </tr></table>
    <a href="javascript:fmFileInfo(<? echo "'$id', '$val[permissions]', '$val[owner]', '$val[group]', '$val[size]', '$val[changed]', '$val[name]', '$val[thumb]', '$val[width]', '$val[height]'"; ?>)" class="fmLink"
     title="<? echo $msg['cmdFileInfo']; ?>"
     onMouseOver="window.status='<? echo $msg['cmdFileInfo']; ?>'; return true"
     onMouseOut="window.status=''">
    <? echo $name; ?></a>
    <br><br>
<?
    if($enableRename) {
?>
      <a href="javascript:fmOpenDialog('fmRename', '<? echo $msg['cmdRename'] . ": $file"; ?>', '<? echo $id; ?>', '<? echo $file; ?>')"
       title="<? echo $msg['cmdRename']; ?>"
       onMouseOver="window.status='<? echo $msg['cmdRename']; ?>'; return true"
       onMouseOut="window.status=''">
      <img src="<? echo $icons; ?>/rename.gif" border="0" width="10" height="10" alt="<? echo $msg['cmdRename']; ?>"></a>
<?
    }
    else {
      $error = $msg['cmdRename'] . ': ' . $msg['errDisabled'];
?>
      <a href="javascript:fmOpenDialog('fmError', '<? echo $error; ?>')" onMouseOver="window.status=''; return true">
      <img src="<? echo $icons; ?>/rename_x.gif" border="0" width="10" height="10"></a>
<?
    }

    if($enablePermissions) {
?>
      <a href="javascript:fmOpenDialog('fmPerm', '<? echo $msg['cmdChangePerm'] . ": $file"; ?>', '<? echo $id; ?>', '<? echo $file; ?>', '<? echo $val['permissions']; ?>')"
       title="<? echo $msg['cmdChangePerm']; ?>"
       onMouseOver="window.status='<? echo $msg['cmdChangePerm']; ?>'; return true"
       onMouseOut="window.status=''">
      <img src="<? echo $icons; ?>/permissions.gif" border="0" width="10" height="10" alt="<? echo $msg['cmdChangePerm']; ?>"></a>
<?
    }
    else {
      $error = $msg['cmdChangePerm'] . ': ' . $msg['errDisabled'];
?>
      <a href="javascript:fmOpenDialog('fmError', '<? echo $error; ?>')" onMouseOver="window.status=''; return true">
      <img src="<? echo $icons; ?>/permissions_x.gif" border="0" width="10" height="10"></a>
<?
    }

    if($enableDelete) {
      if($val['icon'] == 'dir') {
        $mode = 'removeDir';
        $confirm = $msg['msgRemoveDir'];
      }
      else {
        $mode = 'removeFile';
        $confirm = $msg['msgDeleteFile'];
      }
?>
      <a href="javascript:fmGoToOK('<? echo $file . ':\n' . $confirm; ?>', '<? echo "$PHP_SELF?fmMode=$mode&fmObject=$id"; ?>')"
       title="<? echo $msg['cmdDelete']; ?>"
       onMouseOver="window.status='<? echo $msg['cmdDelete']; ?>'; return true"
       onMouseOut="window.status=''">
      <img src="<? echo $icons; ?>/delete.gif" border="0" width="10" height="10" alt="<? echo $msg['cmdDelete']; ?>"></a>
<?
    }
    else {
      $error = $msg['cmdDelete'] . ': ' . $msg['errDisabled'];
?>
      <a href="javascript:fmOpenDialog('fmError', '<? echo $error; ?>')" onMouseOver="window.status=''; return true">
      <img src="<? echo $icons; ?>/delete_x.gif" border="0" width="10" height="10"></a>
<?
    }

    if($val['icon'] == 'text') {

      if($enableEdit) {
?>
        <a href="<? echo "$PHP_SELF?fmMode=edit&&fmObject=$id"; ?>"
         title="<? echo $msg['cmdEdit']; ?>"
         onMouseOver="window.status='<? echo $msg['cmdEdit']; ?>'; return true"
         onMouseOut="window.status=''">
        <img src="<? echo $icons; ?>/edit.gif" border="0" width="10" height="10" alt="<? echo $msg['cmdEdit']; ?>"></a>
<?
      }
      else {
        $error = $msg['cmdEdit'] . ': ' . $msg['errDisabled'];
?>
        <a href="javascript:fmOpenDialog('fmError', '<? echo $error; ?>')" onMouseOver="window.status=''; return true">
        <img src="<? echo $icons; ?>/edit_x.gif" border="0" width="10" height="10"></a>
<?
      }
    }
?>
    </td>
<?
    $cnt++;
  }
  while($cnt < $cellCnt) {
    echo "<td class=\"$class\" width=\"$contWidth\">&nbsp;</td>\n";
    $cnt++;
  }
?>
</tr>
</table>
