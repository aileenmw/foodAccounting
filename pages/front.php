<?php
    /**
 *  $res = 0 : Der er ikke blevet registreret input data
 *  $res = 1 : Check din mail. Du burde have fået tilsendt en foreløbig adgangkode
 *  $res = 5 : Email er ikke registreret
 *  $res = 6 : koden er gemt i xml mailen blev ikke sendt
 *  $res = 7 : Midlertidig kode kunne ikke genereres
        
 */
$resetRes = $_GET['res']  ?? null;
$resMsg = "";
if($resetRes == 1) {
    $resMsg = "Check din mail. Du burde have fået tilsendt en foreløbig adgangkode";
}
?>
<p class="center font20"><b><?=$resMsg?></b></p>
<div class="frontWrapper">
    <div class="lgImgWrapper">
        <div class="error_img">
            <img src="img/gr_4_round_c.png" id="lgImg">
        </div>
        <div class="imgText">Madregnskab Gruppe 4</div>
    </div>
</div>