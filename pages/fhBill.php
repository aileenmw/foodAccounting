<?php

include('xmlHandlers/getFhData.php');
$fhXml = $_SESSION['fhXml'] ?? null;
 $t = strtotime($dueDate);
 $dueDate = date('d-m-Y', $t);
?>
<div class="wrapper">
    <div class="space"></div>
    <?php
    if($role == 1) {
    ?>
    <div class="btnWrapper">
        <div class="btn btn-primary no-print reloadXml genBtn hovertexttop" data-hover="Programmet bliver gjort klar til næste afregning med udgangspunkt i disse data" onclick="printBills(true)" id="printFhBill">Print og Arkivér</div>
    </div> 
    <?php
    }
    ?>
    <div class="billWrapper">
        <div class="headingWrapper">
            <h1 class="h">Fælleshusregning</h1>
        </div> 
        <?php
            if($fhXml) {
        ?>
        <p class="center small"><?=  sourceName($xmlFH)  ?? "" ?></small></p>
        <?php
            }
        ?>
        <table class="fhBill">    
            <tbody>
            <?php
                foreach($fhAfregning  as $key => $val) {
                    if( $key == "Udlæg") {   
                        $i = 1;                    
                        foreach($val->Post as $post) {     
                            echo "<tr class='subText greyBg'><td><b>Udlæg " . $i . "</b></td><td></td></tr>";                                                                            
                            foreach($post as $postName => $postVal) {
                                echo "<tr>";
                                echo "<td class='subText'>&nbsp; &nbsp;" . $postName . "</td>";
                                echo "<td class='subText'>"    . $postVal . "</td>";
                                echo "</tr>";
                            }
                            echo "<tr class='rowLine'></tr>"; 
                            $i++;
                        }
                        echo "<tr>";
                        echo "<td><b>Udlæg i alt</b></td>";
                        echo "<td>"    . $val['Total'] . "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        echo "<td><b>" . $key . "</b></td>";
                        echo "<td>"    . $val . "</td>";
                        echo "</tr>";
                    }
                }
            ?>
            <tr><td><b>Betales senest: </b></td><td><?=$dueDate?></td></tr>
            <tr><td></td></tr>
            </tbody>
        </table>
    </div>
</div>