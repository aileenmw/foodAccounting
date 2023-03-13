<?php
    $t = strtotime($dueDate);
    $dueDate = date('d-m-Y', $t);
?>
    <div class="space"></div>
    <?php  
        if($role == 1) {
    ?>
    <div class="spinnerWrapper">
        <div class="spinner-border text-primary"></div>
    </div>
    <h1 class="h no-print">Madregninger</h1>
    <h4 class="center text-muted no-print"><?=$currentFileDate?></h4>
    <?php
        }
        if($role == 1) {
    ?>
    <h5 class="center bottomline no-print">Husk at gemme tabellen på siden "Regnskab Beboere" for at aktualisere regningerne</h5>
    <div onclick="printPdf()" id="printBills" class="btn btn-primary reloadXml genBtn no-print" no-print>Print og Arkivér</div>
    <!-- <div onclick="printBills()" id="printBills" class="btn btn-primary reloadXml genBtn no-print" no-print>Print og Arkivér</div> -->
    <img src="img/print.jpg" onclick="window.print()" class="print no-print pointer" title="Print uden arkivering">
    <!-- <div onclick="printPdf()" class="btn btn-primary reloadXml genBtn no-print" no-print>Test PDF</div> -->
    <?php
        }
    foreach($houses as $bill) {  
        $house = $bill['Nr'];

        if($role == 1) {
        ?>
        <div class="billWrapper">
            <table onclick="tableContent(this)" id="<?=$house?>"  class="bill">    
                <tbody>
                <?php                
                    echo "<h3 class='h'>Madregning for hus nr. " .  $house . "</h3>";
                    echo "<h6 class='center text-muted'>" . $currentFileDate . "</h6>";
                
                    foreach ($bill as $key => $val) {
                        if ($key == "Regning") {
                            echo "<tr class='billPost'>";
                            echo "<th class='billKey'>Tillæg</th>";
                            echo "<td key='Tillæg' class='billVal'>40</td>";
                            echo "</tr>";
                            echo "<tr class='billPost'>";
                            echo "<th key='Betalingsdato' class='billKey'><b>Betalingsdato</b></th>";
                            echo "<td key='Dato' class='billVal'><b>" . $dueDate . "</b></td>";
                            echo "</tr>";
                        }
                        if($key != "Depositum") {
                        echo "<tr class='billPost'>";
                        echo "<th class='billKey'>" . $key . "</th>";
                        echo "<td  id=" .  $house . "_" . $xmlPosts[$key] . " class='billVal' key='" . $key ."'>" . $val . "</td>";
                        echo "</tr>";
                        }
                }
                
                ?>
                <tr class="account"><td>Arbejdernes Landsbank</td><td>5387 0247082</td></tr>
                <tr class="source"><td><small>Source: <?= sourceName($xml) ?? "" ?></small></td></tr>
                </tbody>
            </table>
        </div>
        <?php
        } else {
            if( $loginHouse == $house) {
        ?>
                <div class="billWrapper">
                <table class="bill">    
                    <tbody>
                    <?php                    
                        echo "<h2 class='h'>Madregning for hus nr. " .  $house . "</h2>";
                        echo "<h4  class='center'>" . $currentFileDate . "</h4>";
                    
                        foreach ($bill as $key => $val) {
                            if ($key == "Regning") {
                                echo "<tr class='billPost'>";
                                echo "<td class='billKey'>Tillæg</td>";
                                echo "<td class='billVal'>40</td>";
                                echo "</tr>";
                                echo "<tr class='billPost'>";
                                echo "<td class='billKey'><b>Betalingsdato</b></td>";
                                echo "<td class='billVal'><b>" . $dueDate . "</b></td>";                              
                                echo "</tr>";
                            }
                            if($key != "Depositum") {
                                echo "<tr class='billPost'>";
                                echo "<td class='billKey'>" . $key . "</td>";
                                echo "<td  id=" .  $house . "_" . $xmlPosts[$key] . " class='billVal'>" . $val . "</td>";
                                echo "</tr>";
                            }
                    }
                    ?>
                    <tr class="account"><td>Arbejdernes Landsbank</td><td>5387 0247082</td></tr>
                    <tr class="source"><td><small>Source: <?=  sourceName($xml)  ?? "" ?></small></td></tr>
                    </tbody>
                </table>
                </div>
            <?php
            }
        }
    }
    ?>
    <script>
        function printPdf() {
            swal({
            title: "Vil du gemme og sende regningerne?",
            text: "Der bliver genereret pdf'er af regningerne",
            icon: "info",
            buttons: ["Nej", "Generér PDF'er"],
        }).then((printPdf) => {
            if(printPdf) {           
                $(".spinner-border").css("display", "block");
                $(".spinnerWrapper").css("display", "block");
                $.post("ajax/generatePDF.php", function(response) {
                    // console.log(response);
                    if(response) {
                        $(".spinner-border").css("display", "none");
                        $(".spinnerWrapper").css("display", "none");                   
                        var resArr = JSON.parse(response);
                        var folder = resArr[0];
                        var res = resArr[1];
                        swal({
                            title: "Vil du sende regningerne?",
                            text: "PDF'er er blevet genereret.",
                            icon: "info",
                            buttons: ["Nej, ikke endnu", "Ja send"],
                        }).then((emailBill) => {
                            if(emailBill) {
                                $(".spinner-border").css("display", "block");
                                $(".spinnerWrapper").css("display", "block");
                                $.post("ajax/emailBills.php", {"folder" : folder}, function(isSent) {
                                    $(".spinner-border").css("display", "none");
                                    $(".spinnerWrapper").css("display", "none");  
                                    if(isSent) {
                                        var res = JSON.parse(isSent);
                                        console.log(res);
                                        swal({
                                            title: "Regningerne er sendt",
                                            text: "Der bliver gjort klar til næste regnskab",
                                            icon: "success",
                                            button: true,
                                        }).then((setXmlForNextBills) => {
                                            if(setXmlForNextBills) {
                                                finalPrint();
                                            }
                                        })
                                    }                                        
                                });
                            }
                        })
                    }
                })
            }        
        });
    }
    </script>