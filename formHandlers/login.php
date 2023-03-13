<?php
require_once("../session.php");
/**
 * $res:
 *  9 : password is wrong
 *  1 : logindata approved
 *  2 : either email or pw is missing
 *  3 : post is not set
 */
$res = 9;
$json = "";

 if (isset($_POST)) {
    if ( !isset($_POST['email']) || !isset($_POST['pw']) || $_POST['email'] =="" || $_POST['pw'] == "" ) {
         $res = 2;
     } else {
        $xmlPath = "../xml/tenants.xml";
        $users = new SimpleXMLElement( $xmlPath, false, true);

        $email = htmlspecialchars($_POST['email']);
        $pw = htmlspecialchars($_POST['pw']);

         foreach ($users as $user) {
             if( $user->Email == $email) {
                 if( $user->Password == md5($pw)) {
                    $res = 1;
                    $_SESSION['login'] = 1;
                    $_SESSION['role'] = intval($user->Rolle);
                    $_SESSION['hus'] = intval($user['Nr']);
                    $_SESSION['user'] = strval($user->Email);
                    $_SESSION['name'] = strval($user->Fornavn);
                    $res = 1;
                } else {
                    $res = 4; 
                }
            } 
        }
    }
} else {
    $res = 2;
}
if($res == 1) {
    header("Location: ../index.php");
} else {
     header("Location: ../index.php?page=login&login=" . $res);
}
?>