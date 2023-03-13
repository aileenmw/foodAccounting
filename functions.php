<?php
    function cleanup ($string) {
        $string = trim($string);
        $string = stripcslashes($string);
        $string = htmlspecialchars($string);

        return $string;
    }

    function filenameToDate($file) {
        $filename = str_replace(".xml", "", $file);
        $nameArray = explode("_", $filename);
        $dateArray = array_slice($nameArray, 0, 3);
        $dateStr = implode("-", $dateArray);                    
        $date = date_create($dateStr);
        $date = date_format($date, "d-m-Y");

        return $date;
    }

    function checkVal ($val) {
        if($val == null || $val == 'NaN' || $val == "") {
            $val = 0;
        }
        return $val;
     }

     function getDateFromXmlName($xml) {
        $fileArr = explode( "/", $xml);
        $fileArr = array_reverse($fileArr);
        $fileArr = explode( "_", $fileArr[0]);
        $currentFileDate =  $fileArr[2] ."-" . $fileArr[1] ."-" . $fileArr[0] ?? ""; 
        return $currentFileDate;
     }
    function pwFormat($pw) {
        $uppercase = preg_match('@[A-Z]@', $pw);
        $lowercase = preg_match('@[a-z]@', $pw);
        $number    = preg_match('@[0-9]@', $pw);
        // $specialChars = preg_match('@[^\w]@', $pw); // and one special character

        if(!$uppercase || !$lowercase || !$number ||  strlen($pw) < 6) {
            echo 'Password skal mindst bestå af 6 karakterer og det skal indeholde mindst 1 stort bogstav og en tal.';
        }
    }
    function createRanPw() { 
        $pass = random_int(1, 5555555);

        return $pass; 
    } 

    function sendResetMail($to, $token, $name) {
        $subject = "Gendan adgangskode";
        $txt = 
        "<h3>Hej " . $name . "!</h3>" .
        "<p>Log in ind på madregnskabet med den midlertidige adgangkode nedenfor og lav en ny kode på siden 'Beboere'.</p>" .
        "<h4>" . $token . "</h4><br/>" .
        "<p>Hilsen,</p>" .
        "<h4>Madregnskabsteamet </h4>";
      	$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <aileenmw@gmail.com>' . "\r\n";
        
         mail($to, $subject, $txt, $headers);
    }

    function getEmail($houseNmb, $tenants) {

            foreach($tenants as $t) {
                 if( intval($houseNmb)  == intval($t['Nr'])) {
                     $email = $t->Email;
                 }
             }

            return $email;
    }

    /**
     * @param string $folder : "path/to/folder/"  !NOTICE slash!
     * @param  $folderout = 0 : folderpath from rootfolder, nmb indicates current subfolders in root 
     */
    function getXmlFile($path, int $folderout=0) {
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
        if($folderout != 0) {

        }
       
        $xml = str_replace("../", "", $xml);
    
        if($folderout != 0) {
            for($i=0; $i < $folderout; $i++) {
                $xml = "../" . $xml;
            }
        }
        $xml = str_replace("../", "", $xml);
    
        return $xml;
    }

       /**
     * @param string $xml : "path/to/folder/xx.xml"  
     * @return SimpleXMLElement
     */
    function getXmlData($xml) {
        $files = scandir($xml, SCANDIR_SORT_DESCENDING);
        $files = array_diff($files, array('..', '.'));
        $xmlEl = new SimpleXMLElement($xml, false, true) ?? null;
     
        return $xmlEl;
    }

    /**
     * @return string
     * @param string 
     *  strinps path and extension from xml path
     */
    function sourceName($xml) {
        $source = explode("/", $xml);
        $c = count($source)-1;
        $sourceName = str_replace(".xml", "" , $source[$c]);
        return $sourceName;
    }

    /**
     * @param string $path folderpath without slash
     * @param string $filename optional | file which is not to be deleted
     */
    function emptyFolder($path, $except="") {
    $prevFiles = glob($path . "/*"); 
        if($prevFiles) {
            $i = 0;
            $deleteCount = count($prevFiles);
            foreach($prevFiles as $prev){ 
                if (is_file($prev) && $prev != $path . "/" . $except) {
                    if ($i < $deleteCount) {
                        unlink($prev); 
                        $i++;
                    }
                }
            }  
        } else {
        $errArr[] = "Kunne ikke slette gamle xml filer";
        }
    }
?>