<?php
include '../functions.php';
include '../session.php';
$errArr = [];

if ($_POST) {
    if($_POST['folder']) {
        if($_POST['folder'] == "tenants") {
            $folder = $_POST['folder'];
        } else if ($_POST['folder'] == "fhXml") {
            $folder = $_POST['folder'];
        } else {
            $errArr[] = "Mappen kunne ikke findes. ";
        }

     $pathPrev = "../xml/" . $folder . "/previous/";
     $pathCur = "../xml/" . $folder . "/current/";
     $pathTmp = "../xml/" . $folder . "/tmp/";


     $files = scandir($pathPrev, SCANDIR_SORT_DESCENDING);
     $files = array_diff($files, array('..', '.'));
     $fileName = $files[0] ?? null;

        if(copy($pathPrev . $fileName, $pathCur . $fileName)){        
            if(copy($pathPrev . $fileName, $pathTmp . $fileName)){
                if(unlink($pathPrev . $fileName)) { 
                    emptyFolder($pathTmp, $fileName);
                    emptyFolder($pathCur, $fileName); 
                } else {
                    $errArr[] = "Seneste regnskab kunne ikke slettes fra mappe (prev). ";
                }
            } else {
                $errArr[] = "Seneste regnskab kunne ikke kopieres til aktuel mappe (tmp). ";
            }
        }else {
            $errArr[] = "Seneste regnskab kunne ikke kopieres til aktuel mappe (cur). ";
        }
    }
} else {
    $errArr[] = "Der blev ikke modtaget post data";
}

$errCount = count($errArr);
if($errCount == 0) {
    $_SESSION['fhXml'] = $fileName;
}
$res = $errCount > 0 ?  json_encode($errArr) : 1;
echo $res;