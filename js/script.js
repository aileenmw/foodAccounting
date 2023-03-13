import { adultPrice, teenPrice, childPrice, surcharge } from "/madregnskab_gruppe4/js/utils.js";

$("#surcharge").html(surcharge);

function setBalance(el) {      
    var payed = $(el).closest("tr").find(".payed").val() ? parseFloat($(el).closest("tr").find(".payed").val()) : 0;
    var debt = $(el).closest("tr").find(".debt").html() ?  parseFloat($(el).closest("tr").find(".debt").html()): 0;
    var balance = debt - payed;
    balance = balance.toFixed(2);

    $(el).closest("tr").find(".balance").html(balance);
    $(el).closest("tr").find(".balanceInput").attr("value", balance);
}

function setEaten(el) {
    var adult = $(el).closest("tr").find(".adult").val() ?? 0;
    var teen = $(el).closest("tr").find(".teen").val() ?? 0;
    var child = $(el).closest("tr").find(".child").val() ?? 0;
    var adultEaten = adult != 0 ? adult*adultPrice : 0;
    var teenEaten = teen != 0 ? teen*teenPrice : 0;
    var childEaten = child != 0 ? child*childPrice : 0;
    var eaten = adultEaten + teenEaten + childEaten ?? 0;
    $(el).closest("tr").find(".eaten").html(eaten);
    $(el).closest("tr").find(".eatenInput").val(eaten);
}

$(document).ready(function(){
    
    $(".balance").click(function(){
        setBalance(this);
    });
    $(".eaten").click(function(){
        setEaten(this);
    });
    /* opdaterer data i tabel udfra input */
    $(".billing").click(function(){
        var balanceEl = $(this).closest("tr").find(".balance");
        // Updating balance and eaten field inputs
        setBalance(balanceEl);
        setEaten(this);
        var balance = balanceEl.html() ?? 0;
        var eaten = $(this).closest("tr").find(".eaten").html() ? parseFloat($(this).closest("tr").find(".eaten").html()) : 0;
        var expenses = $(this).closest("tr").find(".expenses").val() ? parseFloat($(this).closest("tr").find(".expenses").val()) :  0;
        var billing = parseFloat(balance) < 0 ? parseFloat(balance) + parseFloat(eaten) + surcharge - expenses : parseFloat(balance) + parseFloat(eaten) + surcharge - expenses ?? 0;
        billing = parseFloat(billing).toFixed(2);
    
        $(this).closest("tr").find(".billing").html(billing);
        $(this).closest("tr").find(".billingInput").val(billing);
    });

    /**@description  if the button is ckicked for the final printing of bill :
     * - redirection to table overview -> print table (table is loaded async to get data before print) 
     * -> redirect to bills for print
     */
    if(urlGet("print") == 1) {
        var url = urlGet("page") == "billing" ? "index.php?page=bills&print=2" : "index.php?page=fhBill&print=2";
        $.when( $.ajax('ajax/getTmpXmlFile.php')).then( function (xmlFile) {
            loadDoc(xmlFile, false);
            window.print();
            window.location = url;
        });
    } else {
        $.when( $.ajax('ajax/getTmpXmlFile.php')).then( function (xmlFile) {
            loadDoc(xmlFile, "noAsync");
        });
    }

    if (urlGet("print") == 2 ) {
        finalPrint();         
     }
    
});  // end of document ready

$("#clearTable").click(function() {
    location.reload();
});

/* RELOAD TABLE FROM CURRENT XML*/
function loadDoc(currentXml, async=true) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
             resetTable(this);
        }
    };
    xhttp.open("GET", currentXml, async);
    xhttp.send();
}

function resetTable(xml) {
    var i;
    var xmlDoc = xml.responseXML;
    var x = xmlDoc.getElementsByTagName("Hus");

    for (i = 0; i < x.length; i++) { 

        var house =  x[i].getAttribute('Nr');
        var debt =  x[i].getElementsByTagName("Udestående")[0].textContent ?? 0;
        var payed = x[i].getElementsByTagName("Indbetalt")[0].textContent ?? 0;
        var balance = x[i].getElementsByTagName("Saldo")[0].textContent ?? 0;
        var adult = x[i].getElementsByTagName("Voksne")[0].textContent ?? 0;
        var teen = x[i].getElementsByTagName("Pubber")[0].textContent ?? 0;
        var child = x[i].getElementsByTagName("Børn")[0].textContent ?? 0;
        var eaten = x[i].getElementsByTagName("Spist")[0].textContent ?? 0;
        var expenses = x[i].getElementsByTagName("Udlæg")[0].textContent ?? 0;
        var bill = x[i].getElementsByTagName("Regning")[0].textContent ?? 0;

        /* UPDATE TABLE */
        var debtCell = "#" + house + "_debt-cell";
        var debtinput = "#" +  house + "_debt-input";
        var payedinput = "#" +  house + "_payed-input";
        var balanceCell = "#" +  house + "_balance-cell";
        var balanceinput = "#" +  house + "_balance-input";
        var adultinput = "#" +  house + "_adult-input";
        var teeninput = "#" +  house + "_teen-input";
        var childinput = "#" +  house + "_child-input";
        var expensesinput = "#" +  house + "_expenses-input";

        $(debtCell).html(debt);
        $(debtinput).attr("value", debt);
        $(payedinput).attr("value", payed);
        $(balanceCell).html(balance);
        $(balanceinput).attr("value", balance);
        $(adultinput).attr("value", adult);
        $(teeninput).attr("value", teen);
        $(childinput).attr("value", child);
        $(expensesinput).attr("value", expenses);
        
         
        /* UPDATE BILLS  */
        $("#" + house + "_debt").html(debt);
        $("#" + house + "_payed").html(payed);
        $("#" + house + "balance").html(balance);
        $("#" + house + "adult").html(adult);
        $("#" + house + "teen").html(teen);
        $("#" + house + "child").html(child);
        $("#" + house + "eaten").html(eaten);
        $("#" + house + "expenses").html(expenses);
        $("#" + house + "bill").html(bill);
    }
}