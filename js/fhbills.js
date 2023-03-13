
    /* AJAX > save values to xml */
    function saveFhBilling() {
        $.ajax({
            type: 'post',
            url: 'ajax/createFhXml.php',
            data: $("#fhForm").serialize(),
            success: function (response) {
                var res = JSON.parse(response);
                switch (res['success']) {
                   case 1 :
                        swal({
                            title: "Data er belvet gemt",
                            text: "Vil du se regnigen?",
                            icon: "success",
                            buttons: ["Nej, ikke endnu","Ja tak"],
                        }).then((printAndReload) => {
                            if(printAndReload) {
                                window.location = 'index.php?page=fhBill';
                            }        
                        });       
                    break;
                    case 0 :
                        swal({
                            title: "Der blev ikke sendt noget data",
                            icon: "success",
                            button: true,
                        });
                    break;
                    case 2 :
                        swal({
                            title: "Data blev ikke gemt i databasen",
                            icon: "success",
                            button: true,
                        })
                } 
            }                    
        });
    }
