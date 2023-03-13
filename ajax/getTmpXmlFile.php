<?php
 include '../session.php';

        $path = "../xml/tenants/";

        $pathTemp = $path . "tmp/";
        $filesTemp = scandir($pathTemp, SCANDIR_SORT_DESCENDING);
        $filesTemp = array_diff($filesTemp, array('..', '.'));
        
        $pathCurrent = $path ."current/";
        $filesCurrent = scandir($pathCurrent, SCANDIR_SORT_DESCENDING);
        $filesCurrent = array_diff($filesCurrent, array('..', '.'));
    
        $pathAarchive = $path . "previous/";
        $filesArchive = scandir($pathAarchive, SCANDIR_SORT_DESCENDING);
        $filesArchive = array_diff($filesArchive, array('..', '.'));
    
    
        $xml = null;
    
        if(count($filesTemp) > 0) {
                $xml =  $pathTemp . $filesTemp[0];
        } elseif (count($filesCurrent) > 0) {
                $xml =  $pathCurrent . $filesCurrent[0];
        } elseif (count($filesArchive) > 0) {
                $xml =  $pathAarchive . $filesArchive[0];
        } else {
            $xml = "";
        }

    $xml = str_replace("../", "", $xml);
    $_SESSION['xml'] = $xml;

    echo $xml;
?>