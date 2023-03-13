/**
 * @description:
 * Saving tabledata to nre xml in tenants/current, activating print method and redirecting to bills 
 */
    function saveData() {
        if($("#dueDate").val() != "") {
            $(".billing").trigger("click");
            swal({
                title: "Kontrollér data",
                text: "Vil du tjekke dine tal en sidste gang?",
                icon: "warning",
                buttons: ["Jeg tjekker igen", "Gem data"],
            }).then((saveIt) => {
                if (saveIt) {
                    $.ajax({
                        type: 'post',
                        url: 'ajax/createXml.php',
                        data: $('#form').serialize(),
                        success: function (response) {
                            // alert(response);
                            var res = JSON.parse(response);
                            switch (res['success']) {
                                case 1 :
                                    swal({
                                        title: "Data er gemt.",
                                        text: "Vil du se regningerne?",
                                        icon: "success",
                                        buttons: ["Nej, ikke endnu", "Ja tak"],
                                    }).then((redirect) => {
                                        if(redirect) {
                                            window.location = 'index.php?page=bills';
                                        }        
                                    });       
                                break;
                                case 0 :
                                    swal({
                                        title: "Der blev ikke registreret noget data",
                                        icon: "error",
                                        button: true,
                                    });
                                break;
                                case 2 :
                                    swal({
                                        title: "Data blev ikke gemt i databasen",
                                        icon: "error",
                                        button: true,
                                    })
                            } 
                        }
                    });
                } else {
                    // nottin honey
                }
            });
        }else {
            swal({
                title: "Husk at sætte betalingdato",
                icon: "warning",
                dangerMode: true,
            })
        }
    }

    function updateCells(){
        $(".billing").trigger("click");
    };

   /**
     * @description : prints page, resets files and places old files in archive
     * @param {boolean} fh default is set to tenant bills
     */
    function printBills(fh = false) {
        swal({
            title: "Er du færdig med regnskabet?",
            text:  "Data bliver arkiveret og der gøres klar til næste madregnskab. Det kan være en fordel at vente.",
            icon: "warning",
            buttons: ["Nej, ikke endnu", "Print og reset regnskab"],
            cancelButtonColor: "red",
        }).then((doSave) => {
            if (doSave) {
                if(fh != false) {
                    // alert("fh er true");
                    finalPrint(true);
                } else {
                    window.location = 'index.php?page=billing&print=1';
                }
            } 
        });
    };

    /**
     * @description : prints page, resets files and places old files in archive
     * @param {boolean} fh 
     */
        function finalPrint (fh = false) {
            var url;
            if(fh != false) {
                url = "setFhXmlForNextInvoice.php";
            } else {
                url = "setXmlForNextInvoice.php";
            }
            $.ajax({
                type: 'post',
                url: 'ajax/' + url,
                success: function (response) {
                    if(response == 1){
                        if(fh!=false) {
                            window.print();
                            window.location = 'index.php?page=fhBill'
                        } else {
                            window.location = 'index.php?page=bills'
                        }
                    } else {
                        // alert(response);
                        swal({
                            title: "Sorry, noget gik galt",
                            icon: "success",
                        })
                    }
                }
            });
        }

/**
 * @description returns url get value of certain param 
 */
    function urlGet(param) {
        var windowUrl = window.location.href;
        var url = new URL(windowUrl);
        var getParam = url.searchParams.get(param);

        return getParam;
    }
    
    function closeBox(el) {
        $(el).closest(".box").fadeOut(500);
        location.reload();
    };

    /**check if password input are the same  */

     function checkPw() {

        var resArr = [];

        var pw1 = $("#pw1").val() ?? "";
        var pw2 = $("#pw2").val() ?? "";

        if(pw1 != "" && pw2 != "") {
            if( pw1 == pw2) {
               resArr = validatePw(pw1);
            } else { 
                resArr.push("Kodeordene er ikke ens. ");
            }
        } else {
            resArr.push("Udfyld begge felter. ");
        }
        return resArr;
     }
