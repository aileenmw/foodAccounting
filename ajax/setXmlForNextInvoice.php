<?php

/**
 * tmp er den aktuelle afregning
 * tmp skal flyttes til previous
 * den nye temp og current: alt tømt, bortset fra "Udestående", som er den gamle tmp's "Regning"
 * gamle previous filer slettes
 */

$res = 0;
$pathCur = "../xml/tenants/current/";
$curFiles = scandir($pathCur, SCANDIR_SORT_DESCENDING);
$curFiles = array_diff($curFiles, array('..', '.'));
$currentXml = $curFiles[0] ?? null;

$pathTemp = "../xml/tenants/tmp/";
$tmpFiles = scandir($pathTemp, SCANDIR_SORT_DESCENDING);
$tmpFiles = array_diff($tmpFiles, array('..', '.'));
$tmpXml = $tmpFiles[0] ?? null;

$errArr = [];
$currentCopied = false;
$tmpCopied = false;

if($tmpXml) {
    // move xml with latest data to previous folder
    if(copy($pathTemp . $tmpXml, '../xml/tenants/previous/' . $tmpXml)){
        $tmpCopied = true;
    }else {
        $errArr[] = "Tmp xml kunne ikke kopieres til previous mappen";
    }
} else {
    $errArr[] = "Tmp xml blev ikke fundet";
}

if($tmpCopied != false) {
    $regninger = simplexml_load_file($pathTemp. $tmpXml);

    foreach ($regninger->Hus as $house) {
        
        $debt =  $house->Regning;
    
        $house['Betalingsdato'] = "";
        $house->Udestående = $debt;
        $house->Indbetalt = 0;
        $house->Saldo = 0;
        $house->Voksne = 0;
        $house->Pubber = 0;
        $house->Børn = 0;
        $house->Spist = 0;
        $house->Udlæg = 0;
        $house->Regning = $debt + 40;
    }

    if($regninger->asXML($pathTemp. date("Y_m_d_h_i_s") . '.xml')) {
        unlink($pathTemp . $tmpXml); 
    } else {
        $errArr[] = "Tmp xml kunne ikke opdateres";
    }

    if($regninger->asXML($pathCur. date("Y_m_d_h_i_s") . '.xml')) {
        unlink($pathCur . $currentXml); 
    } else {
        $errArr[] = "Current xml kunne ikke opdateres";
    }    
}

// cleanup
$trashFiles = glob("../xml/tenants/trash/*"); 
foreach($trashFiles as $trash){ 
    if(is_file($trash)) {
        unlink($trash); 
    }
}  

$prevFiles = glob("../xml/tenants/previous/*"); 
if($prevFiles) {
    $i = 0;
    $deleteCount = count($prevFiles)-15;
    foreach($prevFiles as $prev){ 
        if (is_file($prev)) {
            if ($i < $deleteCount) {
                unlink($prev); 
                $i++;
            }
        }
    }  
} else {
    $errArr[] = "Kunne ikke slette gamle xml filer";
}

if(count($errArr) > 0) {
    $res = json_encode($errArr);
} else {
    $res = 1;
}

echo $res;