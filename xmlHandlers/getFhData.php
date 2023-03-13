<?php
// include("session.php");
 
 // Fetch xml data
    $pathTemp = "xml/fhXml/tmp/";
    $filesTemp = scandir($pathTemp, SCANDIR_SORT_DESCENDING);
    $filesTemp = array_diff($filesTemp, array('..', '.'));
    
    $pathCurrent = "xml/fhXml/current/";
    $filesCurrent = scandir($pathCurrent, SCANDIR_SORT_DESCENDING);
    $filesCurrent = array_diff($filesCurrent, array('..', '.'));

    $pathAarchive = "xml/fhXml/previous/";
    $filesArchive = scandir($pathAarchive, SCANDIR_SORT_DESCENDING);
    $filesArchive = array_diff($filesArchive, array('..', '.'));

    $xmlFH = null;
    $fhFiles = null;

    if(count($filesTemp) > 0) {
        $xmlFH =  $pathTemp . $filesTemp[0];
    } elseif (count($filesCurrent) > 0) {
        $xmlFH =  $pathCurrent . $filesCurrent[0];
    } elseif (count($filesArchive) > 0) {
        $xmlFH =  $pathAarchive . $filesArchive[0];
    }

    if($xmlFH) {
        $fhAfregning = new SimpleXMLElement($xmlFH, false, true);
    }

    $fhRegning = $fhAfregning->Regning ?? null;


