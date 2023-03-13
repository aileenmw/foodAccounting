<?php

require 'session.php';
include '../conf/madregnskab/data.php';
include_once 'xmlHandlers/getXmlData.php';
include_once 'xmlHandlers/getFhData.php';
include_once 'xmlHandlers/getTenants.php';
include_once 'functions.php';

/**
 * Session values
 */
$loginHouse = $_SESSION['hus'] ?? null;
$login = $_SESSION['login'] ?? null;
$role = $_SESSION['role'] ?? null;
$user = $_SESSION['name'] ?? null;
/**
 * Pagetitle array
 */
$page = $_GET['page'] ?? null;
$pageName = ["billing" => "Afregning Beboere", "bills" => "Regninger", "tenants" => "Beboere", "fhBilling" => "Fælleshusafregning", "fhBill" => "Fælleshusregning", 
"login" => "Login", "forgot-password"=>"Forgot Password", "archive" => "Arkiv", "test" => "Test"];

$title = $page != null ? $pageName[$_GET['page']] : "Madregnskab";

$currentFileDate = $xml != null ? getDateFromXmlName($xml) : "";
$fhCurrentFileDate = $xmlFH != null ? getDateFromXmlName($xmlFH) : "";

$tenantArr = [];
foreach($tenants as $tenant) {
    $tenantArr[] = $tenant;
}
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <title><?=$title?></title>
      <link rel="icon" type="image/x-icon" href="img/web/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
  <?php
    $xmlInfo = "";
    if(isset($_GET['xml'])) {
        if( $_GET['xml'] != 'false') {
          $xmlInfo = "Xml fil: ". $_GET['xml'];
        } else {
          $xmlInfo = "Xml filen blev ikke oprettet. Noget gik galt";
        }
    } 
   ?>
  <nav class="nav navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="index.php"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active" id="first_nav_li">
        <a class="nav-link" href="index.php"><img id="gr4_logo" src="img/web/gr4.png" alt="gruppe4 logo"><span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?page=tenants">Beboere</a>
      </li>
        <?php
        if($login == 1 && $role == 1) {
          ?> 
      <li class="nav-item">
        <a class="nav-link" href="index.php?page=billing">Regnskab Beboere</a>
      </li>
        <?php
        }
        if($login == 1) {

          $tenantNavBill =  $login == 1 && $role == 1 ? "Beboer regninger" : "Din regning";
        ?>
      <li class="nav-item">
        <a class="nav-link" href="index.php?page=bills"><?=$tenantNavBill?></a>
      </li>
      <?php
      }
      if($login == 1 && $role == 1) {
        ?> 
      <li class="nav-item">
        <a class="nav-link" href="index.php?page=fhBilling">Regnskab fælleshus</a>
      </li>
      <?php
      }
      if($login == 1) {
      ?>
      <li class="nav-item">
        <a class="nav-link" href="index.php?page=fhBill">Fælleshus Regning</a>
      </li>
      <?php
       }if($login == 1 && $role == 1) {
      ?>
      <li class="nav-item">
        <a class="nav-link" href="index.php?page=archive">Arkiv</a>
      </li> 
      <?php
       }   
       if($login == 0 || $login == null) {
        ?>  
      <li class="nav-item">
        <a class="nav-link" href="index.php?page=login">Login</a>
      </li>
      <?php
      }
      if($login == 1) {
        ?> 
      <li class="nav-item">
        <a class="nav-link" onclick="logout()" id="logout">&nbsp;&nbsp;&nbsp;<b>Logout</b></a>
      </li>
      <?php
      }
      ?>
    </ul>
    <?php
          if($user != null) {
      ?>
    <p id="greeting">
        <b>Hej <?=$user?></b>
    </p>
    <?php
          }
          ?>
  </div>
</nav>
<div class="space"></div>