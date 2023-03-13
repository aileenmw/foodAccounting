<div class="tenantWrapper">
    <h1 class="h">Beboere i gruppe</h1>
    <table id="tenantTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Hus nr.</th>
                <th>Navn</th>
                <th>Efternavn</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ( $tenants as $house) {
        ?>
        <tr>
            <td class="houseNmb"><?=$house["Nr"]?></td>
            <td class="fName"><?=$house->Fornavn?></td>
            <td class="lName"><?=$house->Efternavn?></td>
            <td class="email"><span class="mailAddress"><?=$house->Email?></span>
            <?php
            if($house->Email != "") {
                ?>
                <i onclick="copyEmail(this)" title="Kopiér email" class="fa fa-copy copyEmail"></i>
            <?php
            }
            ?>
            </td>
            <td><div id="editBoxBtn" class="editBtn btn btn-primary">Redigér</div></td>
            <?php
                if($login == 1 && $house["Nr"] == $loginHouse) {
            ?>
            <td><div id="resetPwBoxBtn" class="editBtn btn btn-primary">Ny kode</div></td>
            <?php
            } 
            ?>
        </tr>
        <?php
            }
        ?>
        </tbody>
    </table>
    <div id="editBox" class="box">
        <div class="container">
            <form id="tenantForm" method="post">
                <h4 id="formHeader"  class='center'>Redigér eller opret ny beboer i nr. <span class="formHouseNr"></span></h4>
                <input name="houseNmb" class="inputHouseNmb" type="hidden"/>
                <div class="form-group">
                    <label class="label" for="fnameInput">Fornavn</label>
                    <input type="text" class="form-control input-lg" id="fnameInput" name="fname">
                </div>
                <div class="form-group">
                    <label class="label" for="lnameInput">Efternavn</label>
                    <input type="text" class="form-control input-lg" id="lnameInput" name="lname">
                </div>
                <div class="form-group">
                    <label class="label" for="lnameInput">Email</label>
                    <input type="text" class="form-control input-lg" id="emailInput" name="email">
                </div>
                <div id="isNewTenantGroup">
                    <input type="checkbox"  value="1" name="isNewTenant" id="isNewTenant" class="pointer hovertexCheckbox" 
                    data-hover="Hvis du klikker her oprettes en ny bruger og data tilknyttet huset slettes">
                    <label class="label"z for="isNewTenant"><b>Opret ny beboer?</b></label>
                </div>
                <div class="btn btn-primary" type="submit" id="updateTenantSubmit" >Gem</div>
            <div id="x" onclick=closeBox(this)>x</div>
            </form>
        </div>
    </div>
    <div id="resetPwBox" class="box">
        <div class="container">
            <form id="resertPwForm" method="post">
                <br/><br/>
                <h4 id="resetFormHeader"  class='center'>Opet et nyt kodeord for hus nr. <span class="formHouseNr"></span></h4>
                <br/><br/>
                <input name="houseNmb" class="inputHouseNmb" type="hidden"/>
                <div class="form-group" id="pw1_group">
                    <label class="label" for="pw1">Ny kode</label>
                    <input type="password" class="form-control input-lg" id="pw1" name="pw1"><i id="eye1" class="fa fa-eye" aria-hidden="true"></i>
                    <small>Password skal mindst bestå af 6 tegn og det skal indeholde mindst 1 stort bogstav og et tal.</small>
                </div>
                <div class="form-group" id="pw2_group">
                    <label class="label" for="pw2">Gentag kode</label>
                    <input type="password" class="form-control input-lg" id="pw2" name="pw2"><i id="eye2" class="fa fa-eye" aria-hidden="true"></i>
                </div>
                <div class="btn btn-primary" type="submit" id="resetPwSubmit" >Gem</div>
            <div id="x" onclick=closeBox(this)>x</div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {

        $("#eye1").mousedown(function(){
            $("#pw1").attr('type', 'text');
        });
        $("#eye1").mouseup(function(){
            $("#pw1").attr('type', 'password');
        });
        $("#eye2").mousedown(function(){
            $("#pw2").attr('type', 'text');
        });
        $("#eye2").mouseup(function(){
            $("#pw2").attr('type', 'password');
        });

        /* @description
        * find which form is required and displays chosen form
        * clicked button's id minus "Btn" is the fomrs id
        */
        $(".editBtn").click(function() {
            var nmb = $(this).closest("tr").find(".houseNmb").html();
            var formBtn = $(this).attr("id");
            var form = "#" + formBtn.replace("Btn", "");

            $(form).find(".formHouseNr").html(nmb);
            $(form).find(".inputHouseNmb").attr("value", nmb);
            $(form).fadeIn(500);
        });
        
        $("#updateTenantSubmit").click(function() {
            $("#resetPwBox").fadeOut(500);
            $.ajax({
                type: 'post',
                url: 'ajax/updateTenant.php',
                data: $('#tenantForm').serialize(),
                success: function (response) {
                    $updatedItem = "";
                    if(response != "0") {                   
                        switch(response) {
                            case "1": 
                                $updatedItem = "Beboernens data her og på regningen";
                            break;
                            case "2": 
                                $updatedItem = "Beboernens data på regningen";
                            break;
                            case "3": 
                                $updatedItem = "Beboernens data";
                            break;
                        }
                        swal({
                            title: $updatedItem + " er opdateret",
                            icon: "success",
                        }).then((notDone) => {
                            if (notDone) {
                                window.location.reload();
                            }  
                        });
                    } else {
                        swal({
                            title: "Noget gik galt",
                            icon: "error",
                            timer: 3000,
                        });
                    }
                }
            });
        });
        
    
        $("#resetPwSubmit").click(function() {
            var validationArr = checkPw();
            if( validationArr.length == 0) {
                $("#editBox").css("display","none");
                $.ajax({
                    type: 'post',
                    url: 'ajax/resetPw.php',
                    data: $('#resertPwForm').serialize(),
                    success: function (response) {
                        $msg = "";
                        if(parseInt(response ) <= 3) {                   
                            switch(response) {
                                case "1": 
                                    $msg = "Kodeordet er opdateret";
                                break;
                                case "2": 
                                    $msg = "Kodeordene er ikke ens. Prøv igen!";
                                break;
                            }
                            swal({
                                title: $msg,
                                icon: "success",
                            }).then((notDone) => {
                                if (notDone) {
                                    window.location.reload();
                                }  
                            });
                        } else if(parseInt(response ) > 3) {                   
                            switch(response) {
                                case "4": 
                                    $msg = "Kodeordene er ikke ens. Prøv igen!";
                                break;
                                case "5": 
                                    $msg = "Noget gik galt";
                                break;
                            }
                            swal({
                                title: $msg,
                                icon: "error",
                            }).then((notDone) => {
                                if (notDone) {
                                    window.location.reload();
                                }  
                            });
                        } else {
                            swal({
                                title: "Noget gik galt",
                                icon: "error",
                                timer: 3000,
                            });
                        }
                    }
                });
            } else {
                swal({
                    title: "Kodeordet opfyler ikke kravene",
                    text: validationArr.toString().replace("," , " "),
                    icon: "error",
                    button: true,
                })
            }
        });
    });
</script>