======================================================================================================================
INTRODUCTION
======================================================================================================================
Use this software to manage files and directories on your webserver or on an FTP server. You can create, rename
and delete directories, upload, download, edit, rename, delete and search files, and change file and directory
permissions*.

FileManager works fine with FTP connections. Please note that if you don't set up an FTP connection in the
configuration, FileManager will use the local file system instead. In this case it can only access directories and
files for which PHP has at least read permission; if you want to upload, edit, rename or delete files, or change
file permissions*, PHP must also have write permission for these files or directories.

FileManager can be used as a stand-alone software, but it's also easy to integrate it into your own website; just
have a look at the USAGE section below to see how you can do this.

This software should work with PHP 4.1.0 or higher.

* On Windows systems changing of file permissions doesn't work properly. This is not a restriction of this software.

======================================================================================================================
FILE SEARCH
======================================================================================================================
You can use FileManager to search files and directories. It will search all directories recursively, starting in
the directory that is currently viewed. At the moment, it is only possible to search for file or directory names.
Wildcards like "*" are not supported; FileManager will find all files and directories containing the search string
in their name. For instance, if you search for "file", the files "filemanager.php", "get_file.php", etc. will match
your search.

While FileManager views a search result, file upload is disabled, and you can not create new directories. If you want
to do so, please return to your current directory listing first.

======================================================================================================================
LICENSE
======================================================================================================================
This script is freeware for non-commercial use. If you like it, please feel free to make a donation!
However, if you intend to use the script in a commercial project, please donate at least EUR 10.
You can make a donation on my website: http://www.gerd-tentler.de/tools/filemanager/.

======================================================================================================================
USAGE
======================================================================================================================
Extract the files to your webserver and adapt the configuration (config_main.inc.php) to your needs. You can change
FTP settings and the start directory there and enable or disable file uploads / downloads, renaming, editing, etc.
It's also possible to let FileManager automatically replace spaces in filenames with underscores or convert filenames
to lowercase when uploading or downloading files.

There are two configuration files:

- config_main.inc.php is the main configuration file; you must adapt it to your needs
- config_local.inc.php is only used for offline testing; if you don't test offline, you don't need to modify
  this file

Please make sure that PHP has write permission for FileManager's tmp directory.

If you want to use FileManager as a stand-alone software, just open filemanager.php with your favorite browser -
that's all, have fun. ;-)

However, if you want to integrate FileManager into your website like I did in the introduction section of
http://www.gerd-tentler.de/tools/filemanager/, you must include filemanager.inc.php into your PHP script where you
want to use it:

  include('filemanager.inc.php');

You can also set a start directory before including - this overrides the settings in config_main.inc.php and
config_local.inc.php:

  $startDir = '/home/users/gerry/htdocs';
  include('filemanager.inc.php');

Additionally, you should start a session and add the following headers to disable browser caching:

  <?
    if(!session_id()) session_start();

    header('Cache-control: private, no-cache, must-revalidate');
    header('Expires: 0');
  ?>
  <html>
  <head>
  ...

If you're still not quite sure how to integrate FileManager into your website, just have a look at the source code
of filemanager.php.

======================================================================================================================
Source code + example available at http://www.gerd-tentler.de/tools/filemanager/.
======================================================================================================================
