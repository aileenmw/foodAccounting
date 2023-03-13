<?php
    include "../functions.php";

        $pathTemp = "../xml/tenants/tmp/";
        $filesTemp = scandir($pathTemp, SCANDIR_SORT_DESCENDING);
        $filesTemp = array_diff($filesTemp, array('..', '.'));

        $pathCurrent = "../xml/tenants/current/";
        $filesCurrent = scandir($pathCurrent, SCANDIR_SORT_DESCENDING);
        $filesCurrent = array_diff($filesCurrent, array('..', '.'));

        $xml = null;

        if(count($filesTemp) > 0) {
            $xml =  $pathTemp . $filesTemp[0];
        } elseif (count($filesCurrent) > 0) {
            $xml =  $pathCurrent . $filesCurrent[0];
        }

        $currentFileDate = getDateFromXmlName($xml) ?? "";

        if($xml) {
            $xmlBills = new SimpleXMLElement($xml, false, true);
        }
        $houses = $xmlBills->Hus ?? null;
        $dueDate = $xmlBills['Betalingsdato'] ?? null;

        $tenantXml = simplexml_load_file( "../xml/tenants.xml");
        $tenants = $tenantXml->Hus;

        $bill_mail = "";

        $emails = [];
        $i = 1;
        foreach($tenants as $index => $tenant) {
            // array_push($emailArr, array($i => (string)$tenant->Email));
            $emails[$i] = (string)$tenant->Email;
            $i = $i + 2;
        }

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <amw@gmail.com>' . "\r\n";

        $sendMailTo = "";
        foreach($houses as $bill) {
            $house = $bill['Nr'];
            $email = $emails[(int)$house] != "" ? $emails[(int)$house] : "x";

            $bill_mail .=
                "<div class='billWrapper'>" .
                "<table class='bill'>" .
                "<tbody>" .
                "<h2 class='h'>Madregning for hus nr." . $house . "</h2>" .
                "<h5 class='center text-muted'>" . $currentFileDate . "</h5>" .
                "<p class='center'>" . $email . "</p>";

                foreach ($bill as $key => $val) {
                    if ($key == "Regning") {
                        $bill_mail .=
                            "<tr class='billPost'><td class='billKey'>Tillæg</td><td class='billVal'>40</td></tr>" .
                            "<tr class='billPost'><td class='billKey'><b>Betalingsdato</b></td>" .
                            "<td class='billVal'><b>" . $dueDate . "</b></td></tr>";
                    }
                    if($key != "Depositum") {
                        $bill_mail .=
                            "<tr class='billPost'><td class='billKey'>" . $key . "</td>" .
                            "<td class='billVal'>" . $val . "</td></tr>";
                    }
                }
                
                "<tr class='account'><td>Arbejdernes Landsbank</td><td>5387 0247082</td></tr>" .
                "<tr class='source'><td><small>Source: " . sourceName($xml) ?? '' . "</small></td></tr>" .
                "</tbody></table>" .
                "</div>";


                if($email == "aileenmw@gmail.com") {
                    $myBill = $bill_mail;
                } else {
                    "Ikke sendt til: " . $email . "<br>";
                };
        }
        mail("aileenmw@gmail.com","Madregning", $myBill, $headers);
        echo $bill_mail;
?>