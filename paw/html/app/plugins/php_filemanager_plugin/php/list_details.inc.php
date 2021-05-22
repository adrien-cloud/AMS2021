<?
/*********************************************************************************************************
 This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
*********************************************************************************************************/

  $sr = ($fmSortOrder == 'asc') ? 'desc' : 'asc';
  $tooltip = ($sr == 'asc') ? $msg['cmdSortAsc'] : $msg['cmdSortDesc'];
  $imgSort = "$icons/sort_$fmSortOrder.gif";
  $pImg = ($fmSortField == 'permissions') ? $imgSort : "$icons/blank.gif";
?>
<table border="0" cellspacing="1" cellpadding="2" width="100%"><tr align="center">
<td class="fmTH3" width="14" title="<? echo $tooltip; ?>"
 onMouseOver="this.className='fmTH4'; window.status='<? echo $tooltip; ?>'; return true"
 onMouseOut="this.className='fmTH3'; window.status=''"
 onMouseDown="this.className='fmTH5'"
 onMouseUp="this.className='fmTH4'"
 onClick="fmGoTo('<? echo "$PHP_SELF?fmMode=sort&fmName=permissions,$sr"; ?>')">
<img src="<? echo $pImg; ?>" border="0" width="8" height="7">
</td>
<td class="fmTH3" title="<? echo $tooltip; ?>"
 onMouseOver="this.className='fmTH4'; window.status='<? echo $tooltip; ?>'; return true"
 onMouseOut="this.className='fmTH3'; window.status=''"
 onMouseDown="this.className='fmTH5'"
 onMouseUp="this.className='fmTH4'"
 onClick="fmGoTo('<? echo "$PHP_SELF?fmMode=sort&fmName=name,$sr"; ?>')">
&nbsp;<? echo $msg['name']; ?>&nbsp;
<? if($fmSortField == 'name') echo ' <img src="' . $imgSort . '" border="0" width="8" height="7">'; ?>
</td>
<td class="fmTH3" width="15%" title="<? echo $tooltip; ?>"
 onMouseOver="this.className='fmTH4'; window.status='<? echo $tooltip; ?>'; return true"
 onMouseOut="this.className='fmTH3'; window.status=''"
 onMouseDown="this.className='fmTH5'"
 onMouseUp="this.className='fmTH4'"
 onClick="fmGoTo('<? echo "$PHP_SELF?fmMode=sort&fmName=size,$sr"; ?>')">
&nbsp;<? echo $msg['size']; ?>&nbsp;
<? if($fmSortField == 'size') echo ' <img src="' . $imgSort . '" border="0" width="8" height="7">'; ?>
</td>
<td class="fmTH3" width="25%" title="<? echo $tooltip; ?>"
 onMouseOver="this.className='fmTH4'; window.status='<? echo $tooltip; ?>'; return true"
 onMouseOut="this.className='fmTH3'; window.status=''"
 onMouseDown="this.className='fmTH5'"
 onMouseUp="this.className='fmTH4'"
 onClick="fmGoTo('<? echo "$PHP_SELF?fmMode=sort&fmName=changed,$sr"; ?>')">
&nbsp;<? echo $msg['lastChange']; ?>&nbsp;
<? if($fmSortField == 'changed') echo ' <img src="' . $imgSort . '" border="0" width="8" height="7">'; ?>
</td>
<td width="55">&nbsp;</td>
</tr>
<?
  if($fmSearch != '') {
?>
    <tr class="<? echo $class; ?>" onMouseOver="this.className='fmTD2'" onMouseOut="this.className='<? echo $class; ?>'">
    <td class="fmContent" align="center" style="cursor:pointer"
     title="<? echo $msg['cmdGoBack']; ?>"
     onMouseOver="window.status='<? echo $msg['cmdGoBack']; ?>'; return true"
     onMouseOut="window.status=''"
     onClick="fmGoTo('<? echo "$PHP_SELF?fmMode=search"; ?>')">
    <img src="<? echo $icons; ?>/cdup.gif" border="0" width="12" height="10" alt="<? echo $msg['cmdGoBack']; ?>">
    </td>
    <td class="fmContent">&nbsp;</td>
    <td class="fmContent">&nbsp;</td>
    <td class="fmContent">&nbsp;</td>
    <td class="fmTD2" colspan="2"><img src="<? echo $icons; ?>/blank.gif" width="10" height="10"></td>
    </tr>
<?
  }
  else if(strlen($fmCurDir) > strlen($startDir)) {
?>
    <tr class="<? echo $class; ?>" onMouseOver="this.className='fmTD2'" onMouseOut="this.className='<? echo $class; ?>'">
    <td class="fmContent" align="center" style="cursor:pointer"
     title="<? echo $msg['cmdParentDir']; ?>"
     onMouseOver="window.status='<? echo $msg['cmdParentDir']; ?>'; return true"
     onMouseOut="window.status=''"
     onClick="fmGoTo('<? echo "$PHP_SELF?fmMode=parent"; ?>')">
    <img src="<? echo $icons; ?>/cdup.gif" border="0" width="12" height="10" alt="<? echo $msg['cmdParentDir']; ?>">
    </td>
    <td class="fmContent" align="left">..</td>
    <td class="fmContent">&nbsp;</td>
    <td class="fmContent">&nbsp;</td>
    <td class="fmTD2" colspan="2"><img src="<? echo $icons; ?>/blank.gif" width="10" height="10"></td>
    </tr>
<?
  }

  if(is_array($fmListing)) foreach($fmListing as $id => $val) {
    $file = $val['name'];

    if($val['icon'] == 'dir') {
      $tooltip = $msg['cmdChangeDir'];
      $url = "fmGoTo('$PHP_SELF?fmMode=open&fmObject=$id')";
    }
    else if($enableDownload) {
      $tooltip = $msg['cmdGetFile'];
      $url = "fmGoTo('$fmWebPath/get_file.php?id=$id')";
    }
?>
    <tr class="<? echo $class; ?>" onMouseOver="this.className='fmTD2'" onMouseOut="this.className='<? echo $class; ?>'">
    <td class="fmContent" align="center"
     title="<? echo $tooltip; ?>"
     style="cursor:<? echo $url ? 'pointer' : 'default'; ?>"
     onMouseOver="window.status='<? echo $tooltip; ?>'; return true"
     onMouseOut="window.status=''"
     onClick="<? echo $url; ?>">
    <img src="<? echo "$icons/" . $val['icon']; ?>.gif" border="0" width="12" height="10" alt="<? echo $tooltip; ?>">
    </td>
    <td class="fmContent" align="left">
    <a href="javascript:fmFileInfo(<? echo "'$id', '$val[permissions]', '$val[owner]', '$val[group]', '$val[size]', '$val[changed]', '$val[name]', '$val[thumb]', '$val[width]', '$val[height]'"; ?>)" class="fmLink"
     title="<? echo $msg['cmdFileInfo']; ?>"
     onMouseOver="window.status='<? echo $msg['cmdFileInfo']; ?>'; return true"
     onMouseOut="window.status=''">
    <? echo $val['name']; ?></a>
    </td>
    <td class="fmContent" align="right">
<?
    if($val['size'] < 1000) echo $val['size'] . ' B';
    else {
      $size = $val['size'] / 1024;
      if($size > 999) echo number_format($size / 1024, 1) . ' MB';
      else echo number_format($size, 1) . ' KB';
    }
?>
    </td>
    <td class="fmContent" align="center"><? echo $val['changed']; ?></td>
    <td class="fmTD2" align="center" colspan="2" nowrap>
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
    else echo ' <img src="' . $icons . '/blank.gif" width="10" height="10">';
?>
    </td>
    </tr>
<?
  }
?>
</table>
