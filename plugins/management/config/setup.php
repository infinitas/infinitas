<?php
    Confiigure::write(
        'FileManager.inages',
        array(
            'acitons' => array(
                'upload' => '',
                'download' => '',
                'edit' => '',
                'delete' => '',
                'copy' => '',
                'move' => '',
                'zip' => '',
                'unzip' => '',
            ),
            'mimeTypes' => array(
                'pdf' => '',
                'zip' => '',
                '7z' => '',

                'doc' => '',
                'docx' => '',
                'exl' => '',
                'powerPoint' => '',

                'mp2' => '',
                'mp3' => '',
                'mp4' => '',
                'mwa' => '',

                'css' => '',
                'html' => '',
                'htm' => '',
                'flv' => '',
                'php' => '',
                'php3' => '',
                'php4' => '',
                'php5' => '',
                'php6' => '',

                'jpg' => '',
                'jpeg' => '',
                'gif' => '',
                'png' => '',
                'bmp' => '',
                'ico' => '',
            ),
            'folders' => array(
                'empty' => '',
                'images' => '',
                'documents' => '',
                'web' => '',
                'mixed' => ''
            ),
            'socialNetworks' => array(
                'facebook' => '',
                'twitter' => '',
                'gmail' => '',
                'google' => '',
                'badoo' => ''
            ),




            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        )
    );
?>