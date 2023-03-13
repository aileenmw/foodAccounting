<?php
 // Fetch xml data
    $path = "../xml/currentXml/";
    // array_diff to get rid off dot that scandir picks up
// -> https://www.php.net/manual/en/function.scandir.phpd
    $files = scandir($path, SCANDIR_SORT_DESCENDING);
    $files = array_diff($files, array('..', '.'));
    $archivePath = "../xml/previousXml/";
    $archiveFiles = scandir($archivePath, SCANDIR_SORT_DESCENDING);

    $directFilePath = "xml/currentXml/";
    $directArchivePAth = "xml/previousXml/";
    echo $xml = count($files) > 0 ? $directFilePath . $files[0] : $directArchivePAth . $archiveFiles[0];
?>