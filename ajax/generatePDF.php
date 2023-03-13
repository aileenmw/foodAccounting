<?php
  include '../functions.php';    
  require_once  __DIR__ ."../../vendor/autoload.php";
  
  use Dompdf\Dompdf;
  use Dompdf\Options;
  
  $pathTemp = "../xml/tenants/tmp/";
  $filesTemp = scandir($pathTemp, SCANDIR_SORT_DESCENDING);
  $filesTemp = array_diff($filesTemp, array('..', '.'));
  
  $pathCurrent = "../xml/tenants/current/";
  $filesCurrent = scandir($pathCurrent, SCANDIR_SORT_DESCENDING);
  $filesCurrent = array_diff($filesCurrent, array('..', '.'));

  $tenantsPath = "../xml/tenants.xml"; 
  $tenants = new SimpleXMLElement( $tenantsPath, false, true); 
  $tenantArr = [];
    foreach($tenants as $tenant) {
        $tenantArr[] = $tenant;
    }

  $res = [];
  $folder = "";
  $xml = null;

  if(count($filesTemp) > 0) {
      $xml =  $pathTemp . $filesTemp[0];
  } elseif (count($filesCurrent) > 0) {
      $xml =  $pathCurrent . $filesCurrent[0];
  } 

  if($xml) {
      $xmlArray = new SimpleXMLElement($xml, false, true);
  }

  $houses = $xmlArray->Hus ?? null;
  $dueDate = date_create($xmlArray['Betalingsdato']) ?? null;

  $head = "<!DOCTYPE html>";
  $head .= "<html><head>";
  $head .= "<meta charset='UTF-8'>";
  $head .= "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css' integrity='sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh' crossorigin='anonymous'>";
  $head .= "<link rel='stylesheet' href='../style.css'>";
  $head .= "</head><body>";

  $pdfGenerated = 0;
  foreach($houses as $house) {  
    $table = $head;  
    $table .= "<div class='billWrapper'>";
    $table .= "<br><br><br><br><h4 style='text-align: center;'>Madregning for hus nr. " .  $house['Nr'] . "</h4>";
    $table .= "<h6 style='text-align: center;'>Udskrevet den " . date("d-m-Y") . "</h6><br>";
    $table .= "<table class='table' style='width: 60%; margin: 0 15% 0 25%; font-size: 15px; font-family: verdana;'>";
    foreach ($house as $key=>$val) {
      $val = $val == "" || $val == null ? 0 : $val;
      if( $key == "Spist") {
        $table .= "<tr style='padding: 0;'>";
        $table .= "<th style='padding: 0 0 0 20px;'>I alt spist for</th><td style='padding: 0;'>" . $val . "</td>";
        $table .= "</tr>";
      }elseif( $key == "Udlæg") {
          $table .= "<tr style='padding: 0;'>";
          $table .= "<th style='padding: 0 0 0 20px;'>Tillæg</th><td style='padding: 0;'>40</td>";
          $table .= "</tr>";
      } elseif ($key == "Regning") {
          $table .= "<br><br><tr style='background-color: #dce4e8;'>";
          $table .= "<th style='padding-left: 20px;'>" . $key . "</th><td'><b>" . $val . " <small>DKK</small></b></td>";
          $table .= "</tr>";
      } else {
          $table .= "<tr style='padding: 0;'>";
          $table .= "<th style='padding: 0 0 0 20px;'>" . $key . "</th><td style='padding: 0;'>" . $val . "</td>";
          $table .= "</tr>";
      }
    }
    $table .=  "<br><tr style='background-color: #dce4e8;'><th style='padding-left: 20px;'><b>Betalingsdato</b></th><td><b>" . date_format($dueDate, "d-m-Y") . "</b></td></tr>";
    $table .= "<tr style='margin-top: 30px;'><td style='padding-left:20px;'>Arbejdernes Landsbank</td><td>5387 0247082</td></tr>";
    $table .= "<br><br><tr><td  style='color:#555;font-size:10px;text-align:center;padding-left: 20px;'>Source: " . sourceName($xml) . "</td></tr>";
    $table .= "</table></div></body></html>";
    // echo $table;

    $folder = generatePdf($table, $house['Nr']) ?? null;
    if($folder) {
      $pdfGenerated++;
    }
  }

  $res[] = $folder;
 
  if($pdfGenerated == 20) {
    $res[] = "Alle pdf'er er genereret";
  } elseif($pdfGenerated > 0 && $pdfGenerated < 19) {
    $res[] = $pdfGenerated + " er blevet genereret";
  } else {
    $res[] = "No pdfs are generated";
  }

  echo json_encode($res);

function generatePdf($html, $house) {

  $options = new Options;
  $options->setChroot(__DIR__);
  $options->setIsRemoteEnabled(true);

  $dompdf = new Dompdf($options);
  $dompdf->setPaper("A4", "portrait");
  $dompdf->loadHtml($html);
  $dompdf->render();

  // $dompdf->addInfo("Title", "An Example PDF"); // "add_info" in earlier versions of Dompdf

  $output = $dompdf->output();
  $foldername =  date("d-m-Y");
  if(!is_dir("../tenantPdfs/" . $foldername)) {
    mkdir("../tenantPdfs/" . $foldername);
  } 
  
  $filename = $house . "_" .date("d-m-Y") . ".pdf";
  $filePath = "../tenantPdfs/" . $foldername . "/" . $filename;
  $savedFile = file_put_contents( $filePath, $output) ?? null;
    if($savedFile) {
        return "tenantPdfs/" . $foldername . "/";
    } else {
        return null;
    }
  }
?>