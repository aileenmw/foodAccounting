<?php
 $pathTemp = "xml/tenants/tmp/";
 $filesTemp = scandir($pathTemp, SCANDIR_SORT_DESCENDING);
 $filesTemp = array_diff($filesTemp, array('..', '.'));
 
 $pathCurrent = "xml/tenants/previous/";
 $filesCurrent = scandir($pathCurrent, SCANDIR_SORT_DESCENDING);
 $filesCurrent = array_diff($filesCurrent, array('..', '.'));

 $pathAarchive = "xml/tenants/previous/";
 $filesArchive = scandir($pathAarchive, SCANDIR_SORT_DESCENDING);
 $filesArchive = array_diff($filesArchive, array('..', '.'));

 $xml = null;

 switch(true) {
     case (count($filesTemp) > 0) :
         $xml =  $pathTemp . $filesTemp[0];
     break;
     case (count($filesCurrent) > 0) :
         $xml =  $pathCurrent . $filesCurrent[0];
     break;
     case (count($filesArchive) > 0) :
         $xml =  $pathAarchive . $filesArchive[0];
     break;
 }

 $xmlArr = new SimpleXMLElement($xml, false, true);
 
 echo json_encode($xmlArr);