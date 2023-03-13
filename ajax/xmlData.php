<?php

    if($_POST['xml'] && $_POST['xml']!= "") {
            $xml = "../" . $_POST['xml'];
            $xmlEl = new SimpleXMLElement($xml, false, true) ?? null;
            $data = json_encode($xmlEl);

            echo $data;
    }

    ?>