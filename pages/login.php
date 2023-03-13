<div id="loginWrapper">
    <div class="space"></div>
    <?php
    /**
 * $res:
 *  4 : password is wrong
 *  1 : logindata approved
 *  2 : either email or pw is missing
 *  3 : post is not set
 *  9 : noget gik galt
 */
$logRes = $_GET['login'] ?? null;
$logMsg = "";

if($logRes) {
    switch($logRes) {
        case 2:
            $logMsg = "Udfyld begge felter"; 
        break;
        case 4:
            $logMsg = "Kodeordet er forkert"; 
        break;
        case 9:
            $logMsg = "Noget gik galt"; 
        break;
    }
}
?>
    <h1 class="h">Login</h1>
    <p class="center font20"><b><?=$logMsg?></b></p>
    <form id="login" method="post" action="formHandlers/login.php">
        <div class="form-outline mb-4">
            <input type="email" id="email" name="email" class="form-control  loginInput" />
            <label class="form-label" for="email">Email</label>
        </div>
        <div class="form-outline mb-4">
            <input type="password" id="pw" name="pw" class="form-control  loginInput" />
            <label class="form-label" for="pw">Password</label>
        </div>
        <div class="row mb-4">
            <div class="col d-flex justify-content-center">        
            </div>
        </div>
        <button type="submit" id="loginBtn" class="btn btn-primary btn-block">Login</button>
    </form>
    <div class="center">
        <a href="index.php?page=forgot-password">Forgot password?</a>
    </div>
    </div>
    <p class="center"><a class="button" href="mailto:aileenmw@gmail.com">Kontakt administrator</a> for registrering</p>
</div>