<?php
include_once '../functions.php';
/**
 *  $res = 0 : Der er ikke blevet registreret input data
 *  $res = 1 : Check din mail. Du burde have fået tilsendt en foreløbig adgangkode
 *  $res = 5 : Denne email er ikke registreret
 *  $res = 6 : koden er gemt i xml mailen blev ikke sendt
 *  $res = 7 : Midlertidig kode kunne ikke genereres
        
 */

$tenantsPath = "../xml/tenants.xml"; 
$tenants = simplexml_load_file($tenantsPath);  

$res = 0;

if(isset($_POST['submit']) && $_POST['email'] != "" ) {
    $email = cleanup($_POST['email']);
    $isTenant = 0;

    $tenants = simplexml_load_file($tenantsPath); 
    foreach($tenants->Hus as $tenant)
      {
        if ($tenant->Email == $email) {
            $isTenant = 1;
            $tempPw = createRanPw();
            //$tenant->Password = md5($tempPw);
            $resetTenant = $tenant; 
        } 
    }

    $name = $resetTenant->Fornavn;
    if($isTenant == 0) {
        // "Denne email er ikke registreret.";
        $res = 5;
    } else {
        if(file_put_contents($tenantsPath, $tenants->saveXML())) {
                sendResetMail($email, $tempPw, $name);
                // echo "Check din mail. Du burde have fået tilsendt en foreløbig adgangkode";
                $res = 1;
        } else {
           // echo "Midlertidig kode kunne ikke genereres";
            $res = 7;
        }
    }
} else {
    $res = 9;
}

if($res != 1) {
    $_POST = [];
    header("Location: ../index.php?page=forgot-password&formRes=". $res);
} else {
    $_POST = [];
    header("Location: ../index.php?res=". $res);
}
?>