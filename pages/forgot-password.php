<?php
$resMsg = "";
$resetRes = $_GET['formRes']  ?? null;

if($resetRes) {
    $resMsg = "";
    switch ($resetRes) {
        case 9:
            $resMsg = "Skriv din email i feltet for at anmode om en engangskode";
        break;
        case 5:
            $resMsg = "Emailen er ikke registreret";
        break;
        case 6:
            $resMsg = "En ny kode er genereret, men mailen kunne ikke sendes. " . $contactAdmin;
        break;  
        case 7:
            $resMsg = "Midlertidig kode kunne ikke genereres";
        break;    
    }
}
?>
<div id="loginWrapper">
    <div class="space"></div>
    <h1 class="h">Har du glemt dit password?</h1>
    <p class="center"><b>Få tilsendt en midlertidig adgangskode</b></p>
    <form id="login" method="post" action="formHandlers/reset_pw.php">
    <p class="center font20"><b><?=$resMsg?></b></p>
        <div class="form-outline mb-4">
            <input type="email" id="email" name="email" class="form-control  loginInput" />
            <label class="form-label" for="email">Email</label>
        </div>
        <div class="row mb-4">
            <div class="col d-flex justify-content-center">        
            </div>
        </div>
        <button type="submit" name="submit" id="loginBtn" class="btn btn-primary btn-block">Send</button>
    </form>
    </div>
    <p class="center"><a class="button" href="mailto:aileenmw@gmail.com">Kontakt administrator</a> angående login oplysninger</p>
</div>