<?php
/**
 * $res = 0 : nothing is uodated
 * $res = 1 : tenant and bill is updated
 * $res = 2 : bill is updated,  tenant is not updated
 * $res = 3 : tenant is updated, bill is not updated
 * $res = 4 : no post data
 * $res = 5 : House number is missing (not used yet)
 */

$res = 0;
$tenantUpdated = 0;
$billUpdated = 0;

if(isset($_POST)){
    $nmb = $_POST['houseNmb'] ?? null;
    $isNewTenat = $_POST['isNewTenant'] ?? 0;
    $fname = $_POST['fname'] ?? null;
    $lname = $_POST['lname'] ?? null;
    $email = $_POST['email'] ?? null;

    if($nmb && $nmb != "" ) {

        $pathTenants = "../xml/";
        $tenants = simplexml_load_file($pathTenants . "tenants.xml");

        foreach($tenants->Hus as $tenant) {
            if($nmb == $tenant['Nr']) {
                if($fname) {
                    $tenant->Fornavn = $fname;
                }            
                if($lname) {
                    $tenant->Efternavn = $lname;
                }
                if($email) {
                    $tenant->Email = $email;
                }
            }
            if($tenants->asXML($pathTenants .'tenants.xml')) {
                $tenantUpdated = 1;
            }
        }

        $pathBills = "../xml/tenants/current/";
        $files = scandir($pathBills, SCANDIR_SORT_DESCENDING);
        $files = array_diff($files, array('..', '.'));
        $tenants = $files[0];
        $regninger = simplexml_load_file($pathBills . $tenants);

            foreach ($regninger->Hus as $house) {
                if ($house['Nr'] == $nmb) {
                    if($isNewTenat == 1) {
                        $house->Navn = $fname;
                        $house->Efternavn = $lname;
                        $house->Udestående = 0;
                        $house->Indbetalt = 0;
                        $house->Saldo = 0;
                        $house->Voksne = 0;
                        $house->Pubber = 0;
                        $house->Børn = 0;
                        $house->Spist = 0;
                        $house->Udlæg = 0;
                        $house->Regning = 0;
                    } else {
                        if($fname || $lname) {
                            if($fname) {
                                $house->Navn = $fname ?? "";
                            }
                            if($lname) {
                                $house->Efternavn = $lname ?? "";
                            }
                        } 
                    }
                }
            }     

        if(copy($pathBills . $tenants, '../xml/tenants/previous/' . $tenants)){
            unlink($pathBills . $tenants);
            if($regninger->asXML($pathBills . date("Y_m_d_h_i_s") . '.xml')) {
                $billUpdated = 1;
            } 
        }

        if( $tenantUpdated == 1 && $billUpdated == 1) {
            $res = 1;
        } elseif ( $tenantUpdated == 1 && $billUpdated == 0) {
            $res = 2;
        } elseif ( $tenantUpdated == 0 && $billUpdated == 1) {
            $res = 3;
        } elseif ( $tenantUpdated == 0 && $billUpdated == 0) {
            $res = 0;
        }
    } else {
        $res = 4;
    }
} 

echo $res;