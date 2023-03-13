<?php
/**
 * $res = 0 : no post data
 * $res = 1 : pw updated
 * $res = 2 : 
 * $res = 3 :
 * $res = 4 : pw ikke ens
 * $res = 5 : House number is missing (not used yet)
 */

$res = 0;

if(isset($_POST)){
    $nmb = $_POST['houseNmb'] ?? null;
    $pw1 = $_POST['pw1'] ?? null;
    $pw2 = $_POST['pw2'] ?? null;

    if($nmb && $nmb != "" ) {

        if($pw1 && $pw2 && $pw1 != "" && $pw2 != "") {
            if($pw1 != $pw2) {
                $res = 4;
            } else {

                $pathTenants = "../xml/tenants.xml";
                $tenants = simplexml_load_file($pathTenants);

                foreach($tenants->Hus as $tenant) {
                    if($nmb == $tenant['Nr']) {
                        // $uresetPwTenant = $tenant;
                        $tenant->Password = md5($pw1);
                    }
                    if(file_put_contents($pathTenants, $tenants->saveXML())) {
                        $res = 1;
                    }
                }
            }
        }
    } else {
        $res = 5;
    }
}

echo $res;