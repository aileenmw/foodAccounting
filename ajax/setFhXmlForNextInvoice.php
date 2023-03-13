<?php
/**
 * current skal flyttes til previous
 * tmp er den aktuelle afregning > tmp skal flyttes til current
 * den nye temp: allt tømt, bortset fra "Udestående", som er den gamle tmp's "Regning"
 * tmp skal tømmes bortset fra debt
 * 
 */

$res = 0;
$pathCur = "../xml/fhXml/current/";
$curFiles = scandir($pathCur, SCANDIR_SORT_DESCENDING);
$curFiles = array_diff($curFiles, array('..', '.'));
$currentXml = $curFiles[0] ?? null;

$pathTemp = "../xml/fhXml/tmp/";
$tmpFiles = scandir($pathTemp, SCANDIR_SORT_DESCENDING);
$tmpFiles = array_diff($tmpFiles, array('..', '.'));
$tmpXml = $tmpFiles[0] ?? null;

$errArr = [];
$currentCopied = false;
$tmpCopied = false;

if($tmpXml) {
    // move xml with latest data to previous folder
    if(copy($pathTemp . $tmpXml, '../xml/fhXml/previous/' . $tmpXml)){
        $tmpCopied = true;
    }else {
        $errArr[] = "Tmp xml kunne ikke kopieres til previous mappen";
    }
} else {
    $errArr[] = "Tmp xml blev ikke fundet";
}

// Udestående, Indbetalt , Udlæg Total="1112.00" , Post : Dato Indkøbssted Beløb


if($tmpCopied != false) {
    $fhAfregning = simplexml_load_file($pathTemp. $tmpXml, "SimpleXMLElement");
    $fhAfregning['Betalingsdato'] = "";

    $debt =  $fhAfregning->Regning ?? 0;
    $fhAfregning->Udestående = $debt ?? 0;
    $fhAfregning->Indbetalt = 0;
    $fhAfregning->Udlæg['Total'] = 0;

    $c = count($fhAfregning->Udlæg->Post)-1;
    for($i=$c; $i>=0; $i--) {
        $posts[] = $fhAfregning->Udlæg->Post[$i];
        unset($fhAfregning->Udlæg->Post[$i]);
    }
}

if($fhAfregning->asXML($pathTemp. date("Y_m_d_h_i_s") . '.xml')) {
    unlink($pathTemp . $tmpXml); 
} else {
    $errArr[] = "Tmp xml kunne ikke opdateres";
}

if($fhAfregning->asXML($pathCur. date("Y_m_d_h_i_s") . '.xml')) {
    unlink($pathCur . $currentXml); 
} else {
    $errArr[] = "Current xml kunne ikke opdateres";
}    

// cleanup
$trashFiles = glob("../xml/fhXml/trash/*"); 
foreach($trashFiles as $trash){ 
    if(is_file($trash)) {
        unlink($trash); 
    }
}  

$prevFiles = glob("../xml/fhXml/previous/*"); 
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
