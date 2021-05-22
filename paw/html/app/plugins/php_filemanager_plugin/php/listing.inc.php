<?
/*********************************************************************************************************
 This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
*********************************************************************************************************/

  clearstatcache();
  $icons = $fmWebPath . '/icons';

  if(!$fmListing) {
    if(!fm_list($fmCurDir, $fmSearch)) {
      $fmError = $msg['errOpen'] . ': ' . basename($fmCurDir);
      $fmCurDir = $_SESSION['fmCurDir'] = $fmPrevDir;
      fm_list($fmCurDir);
    }
    $_SESSION['fmListing'] = $fmListing;
    $sort = true;
  }

  if($fmSearch != '') {
    $title = $msg['searchResult'] . ": $fmSearch";
    $class = 'fmSearchResult';
  }
  else {
    if(!$fmSysType) {
      if($ftp) $fmSysType = @ftp_systype($ftp);
      else $fmSysType = function_exists('php_uname') ? php_uname() : PHP_OS;
      $_SESSION['fmSysType'] = $fmSysType;
    }
    $arr = explode('/', $fmCurDir);
    $title = $arr[0] . '/.../' . substr($fmCurDir, strlen($startDir) + 1);
    $class = 'fmTD1';
  }
  if(strlen($fmSysType) > 15) $fmSysType = substr($fmSysType, 0, 15) . '...';

  if(!$fmSortField) {
    $fmSortField = 'permissions';
    $fmSortOrder = 'desc';
    $sort = true;
  }
  if($sort) {
    $fmListing = fm_sort_field($fmListing, $fmSortField, $fmSortOrder);
    $_SESSION['fmListing'] = $fmListing;
    $_SESSION['fmSortField'] = $fmSortField;
    $_SESSION['fmSortOrder'] = $fmSortOrder;
  }
?>
<script language="JavaScript"> <!--
function fmFileInfo(id, permissions, owner, group, size, changed, name, thumb, width, height) {
  width = parseInt(width);
  height = parseInt(height);
  var info = '<table border="0" cellspacing="1" cellpadding="1"><tr>' +
             '<td class="fmContent"><b><? echo $msg['name']; ?>:</b></td><td class="fmContent" nowrap>' + name + '</td>' +
             '</tr><tr>' +
             '<td class="fmContent"><b><? echo $msg['permissions']; ?>:</b></td><td class="fmContent">' + permissions + '</td>' +
             '</tr><tr>' +
             '<td class="fmContent"><b><? echo $msg['owner']; ?>:</b></td><td class="fmContent">' + owner + '</td>' +
             '</tr><tr>' +
             '<td class="fmContent"><b><? echo $msg['group']; ?>:</b></td><td class="fmContent">' + group + '</td>' +
             '</tr><tr>' +
             '<td class="fmContent"><b><? echo $msg['lastChange']; ?>:</b></td><td class="fmContent" nowrap>' + changed + '</td>' +
             '</tr><tr>' +
             '<td class="fmContent"><b><? echo $msg['size']; ?>:</b></td><td class="fmContent">' + size + '</td>' +
             ((thumb && width && height) ?
                '</tr><tr><td colspan="2" height="8"></td></tr><tr><td class="frmContent" colspan="2">' +
                '<img src="<? echo $fmWebPath; ?>/thumbnail.php?width=' + width + '&height=' + height + '&id=' + id + '&' + thumb + '" width="' + width + '" height="' + height + '"></td>' : '') +
             '</tr></table>';
  fmOpenDialog('fmInfo', info);
}
//--> </script>
<table border="0" cellspacing="0" cellpadding="0" width="<? echo $fmWidth; ?>"><tr>
<td class="fmTH1" style="padding:4px">
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
<td class="fmTH1" align="left"><? echo "[$fmSysType] $title"; ?></td>
<td class="fmTH1" width="18" align="right" style="cursor:pointer" title="<? echo $msg['cmdRefresh']; ?>"
 onMouseOver="window.status='<? echo $msg['cmdRefresh']; ?>'; return true"
 onMouseOut="window.status=''"
 onClick="fmGoTo('<? echo "$PHP_SELF?fmMode=refresh"; ?>')">
<img src="<? echo $icons; ?>/refresh.gif" border="0" width="11" height="14" alt="<? echo $msg['cmdRefresh']; ?>">
</td>
<td class="fmTH1" width="18" align="right" style="cursor:pointer" title="<? echo $msg['cmdDetails']; ?>"
 onMouseOver="window.status='<? echo $msg['cmdDetails']; ?>'; return true"
 onMouseOut="window.status=''"
 onClick="fmGoTo('<? echo "$PHP_SELF?fmView=details"; ?>')">
<img src="<? echo $icons; ?>/list_details.gif" border="0" width="11" height="14" alt="<? echo $msg['cmdDetails']; ?>">
</td>
<td class="fmTH1" width="18" align="right" style="cursor:pointer" title="<? echo $msg['cmdIcons']; ?>"
 onMouseOver="window.status='<? echo $msg['cmdIcons']; ?>'; return true"
 onMouseOut="window.status=''"
 onClick="fmGoTo('<? echo "$PHP_SELF?fmView=icons"; ?>')">
<img src="<? echo $icons; ?>/list_icons.gif" border="0" width="11" height="14" alt="<? echo $msg['cmdIcons']; ?>">
</td>
<td class="fmTH1" width="22" align="right" style="cursor:pointer" title="<? echo $msg['cmdSearch']; ?>"
 onMouseOver="window.status='<? echo $msg['cmdSearch']; ?>'; return true"
 onMouseOut="window.status=''"
 onClick="fmOpenDialog('fmSearch', '<? echo $msg['cmdSearch']; ?>')">
<img src="<? echo $icons; ?>/search.gif" border="0" width="13" height="14" alt="<? echo $msg['cmdSearch']; ?>">
</td>
<?
  if($enableNewDir && $fmSearch == '') {
?>
    <td class="fmTH1" width="22" align="right" style="cursor:pointer" title="<? echo $msg['cmdNewDir']; ?>"
     onMouseOver="window.status='<? echo $msg['cmdNewDir']; ?>'; return true"
     onMouseOut="window.status=''"
     onClick="fmOpenDialog('fmNewDir', '<? echo $msg['cmdNewDir']; ?>')">
    <img src="<? echo $icons; ?>/newDir.gif" border="0" width="15" height="14" alt="<? echo $msg['cmdNewDir']; ?>">
    </td>
<?
  }
  else {
    $error = $msg['cmdNewDir'] . ': ' . $msg['errDisabled'];
?>
    <td class="fmTH1" width="22" align="right"><a href="javascript:fmOpenDialog('fmError', '<? echo $error; ?>')" onMouseOver="window.status=''; return true">
    <img src="<? echo $icons; ?>/newDir_x.gif" border="0" width="15" height="14"></a></td>
<?
  }

  if($enableUpload && $fmSearch == '') {
?>
    <td class="fmTH1" width="18" align="right" style="cursor:pointer" title="<? echo $msg['cmdUploadFile']; ?>"
     onMouseOver="window.status='<? echo $msg['cmdUploadFile']; ?>'; return true"
     onMouseOut="window.status=''"
     onClick="fmOpenDialog('fmNewFile', '<? echo $msg['cmdUploadFile']; ?>')">
    <img src="<? echo $icons; ?>/new.gif" border="0" width="11" height="14" alt="<? echo $msg['cmdUploadFile']; ?>">
    </td>
<?
  }
  else {
    $error = $msg['cmdUploadFile'] . ': ' . $msg['errDisabled'];
?>
    <td class="fmTH1" width="18" align="right"><a href="javascript:fmOpenDialog('fmError', '<? echo $error; ?>')" onMouseOver="window.status=''; return true">
    <img src="<? echo $icons; ?>/new_x.gif" border="0" width="11" height="14"></a></td>
<?
  }
?>
</tr>
</table>
</td>
</tr><tr>
<td class="fmTH1" align="left">
<table border="0" cellspacing="1" cellpadding="0" width="100%"><tr>
<td class="fmTH2">
<?
  if($fmView == 'icons') {
    include('list_icons.inc.php');
  }
  else {
    include('list_details.inc.php');
  }
?>
</td>
</tr></table>
</td>
</tr></table>
