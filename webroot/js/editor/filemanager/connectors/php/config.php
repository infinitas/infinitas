<?php
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2009 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * Configuration file for the File Manager Connector for PHP.
 */

    global $Config ;

    // SECURITY: You must explicitly enable this "connector". (Set it to "true").
    // WARNING: don't just set "$Config['Enabled'] = true ;", you must be sure that only
    //		authenticated users can access this file or use some kind of session checking.
    $Config['Enabled'] = true ;

    // Path to user files relative to the document root.
    $Config['UserFilesPath'] = '/img/content/' ;
    // Fill the following value it you prefer to specify the absolute path for the
    // user files directory. Useful if you are using a virtual directory, symbolic
    // link or alias. Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
    // Attention: The above 'UserFilesPath' must point to the same directory.
    $Config['UserFilesAbsolutePath'] = dirname( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ).'/img/content/' ;

    // Due to security issues with Apache modules, it is recommended to leave the
    // following setting enabled.
    $Config['ForceSingleExtension'] = true ;

    // Perform additional checks for image files.
    // If set to true, validate image size (using getimagesize).
    $Config['SecureImageUploads'] = true;

    // What the user can do with this connector.
    $Config['ConfigAllowedCommands'] = array('QuickUpload', 'FileUpload', 'GetFolders', 'GetFoldersAndFiles', 'CreateFolder') ;

    // Allowed Resource Types.
    $Config['ConfigAllowedTypes'] = array('File', 'Image', 'Flash', 'Media') ;

    // For security, HTML is allowed in the first Kb of data for files having the
    // following extensions only.
    $Config['HtmlExtensions'] = array("html", "htm", "xml", "xsd", "txt", "js") ;

    // After file is uploaded, sometimes it is required to change its permissions
    // so that it was possible to access it at the later time.
    // If possible, it is recommended to set more restrictive permissions, like 0755.
    // Set to 0 to disable this feature.
    // Note: not needed on Windows-based servers.
    $Config['ChmodOnUpload'] = 0777 ;

    // See comments above.
    // Used when creating folders that does not exist.
    $Config['ChmodOnFolderCreate'] = 0777 ;

    $Config['AllowedExtensions']['File']	    = array('7z', 'aiff', 'asf', 'avi', 'bmp', 'csv', 'doc', 'fla', 'flv', 'gif', 'gz', 'gzip', 'jpeg', 'jpg', 'mid', 'mov', 'mp3', 'mp4', 'mpc', 'mpeg', 'mpg', 'ods', 'odt', 'pdf', 'png', 'ppt', 'pxd', 'qt', 'ram', 'rar', 'rm', 'rmi', 'rmvb', 'rtf', 'sdc', 'sitd', 'swf', 'sxc', 'sxw', 'tar', 'tgz', 'tif', 'tiff', 'txt', 'vsd', 'wav', 'wma', 'wmv', 'xls', 'xml', 'zip') ;
    $Config['DeniedExtensions']['File']		    = array() ;
    $Config['FileTypesPath']['File']		    = $Config['UserFilesPath'] . 'file/' ;
    $Config['FileTypesAbsolutePath']['File']    = ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'file/' ;
    $Config['QuickUploadPath']['File']		    = $Config['FileTypesPath']['File'];
    $Config['QuickUploadAbsolutePath']['File']  = $Config['FileTypesAbsolutePath']['File'] ;

    $Config['AllowedExtensions']['Image']	    = array('bmp','gif','jpeg','jpg','png') ;
    $Config['DeniedExtensions']['Image']	    = array() ;
    $Config['FileTypesPath']['Image']		    = $Config['UserFilesPath'] . 'img/' ;
    $Config['FileTypesAbsolutePath']['Image']   = ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'img/' ;
    $Config['QuickUploadPath']['Image']		    = $Config['FileTypesPath']['Image'];
    $Config['QuickUploadAbsolutePath']['Image'] = $Config['FileTypesAbsolutePath']['Image'] ;

    $Config['AllowedExtensions']['Flash']	    = array('swf','flv') ;
    $Config['DeniedExtensions']['Flash']	    = array() ;
    $Config['FileTypesPath']['Flash']		    = $Config['UserFilesPath'] . 'flash/' ;
    $Config['FileTypesAbsolutePath']['Flash']   = ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'flash/' ;
    $Config['QuickUploadPath']['Flash']		    = $Config['FileTypesPath']['Flash'];
    $Config['QuickUploadAbsolutePath']['Flash'] = $Config['FileTypesAbsolutePath']['Flash'];

    $Config['AllowedExtensions']['Media']	    = array('aiff', 'asf', 'avi', 'bmp', 'fla', 'flv', 'gif', 'jpeg', 'jpg', 'mid', 'mov', 'mp3', 'mp4', 'mpc', 'mpeg', 'mpg', 'png', 'qt', 'ram', 'rm', 'rmi', 'rmvb', 'swf', 'tif', 'tiff', 'wav', 'wma', 'wmv') ;
    $Config['DeniedExtensions']['Media']	    = array() ;
    $Config['FileTypesPath']['Media']		    = $Config['UserFilesPath'] . 'media/' ;
    $Config['FileTypesAbsolutePath']['Media']   = ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'media/' ;
    $Config['QuickUploadPath']['Media']		    = $Config['FileTypesPath']['Media'];
    $Config['QuickUploadAbsolutePath']['Media'] = $Config['QuickUploadAbsolutePath']['Media'];
?>