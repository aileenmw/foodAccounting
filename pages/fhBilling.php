<?php
    $oldDebt = $fhRegning[0] ?? 0;
    $fhXml = $_SESSION['fhXml'] ?? "";
?>
<div class="wrapper wrapperFH" >
    <h1 class="h">Fælleshusregnskab</h1>
    <p class="center small" id="currentXml">Sidst redigeret <?=sourceName($xmlFH) ?? "" ?></p>  
    <form id="fhForm" method="post" action="">  
        <div class="btn btn-primary floatRight no-print" onclick="saveFhBilling()">Gem afregning</div>
        <table id="fhTable" class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col" class="fhDebt">Udestående</th>
                    <th scope="col" id="fhPayed">Indbetalt</th>
                    <th scope="col" id="fhExspences">Udlæg</th>
                    <th scope="col" class="fhBalance">Regning</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="oldDebt"><?=$fhAfregning->Udestående ?? null ?></td>
                    <input id="debtInput" name="oldDebt" value="<?=$fhAfregning->Udestående ?? null?>" type="hidden"/>
                    <td><input id="payedInput" name="payed" class="input fhPayed" value="<?=$fhAfregning->Indbetalt?>" type="number" step="0.01"/></td>
                    <td id="fhExpencesCell" class="pointer hovertext" data-hover="Klik på 'Opret udlæg' knappen for at tilføje et udlæg"></td>
                    <input id="expencesTotalInput"  name="expencesTotal" value="0" type="hidden" />
                    <td onclick="newDebt()" id="newDebtCell"><?=$oldDebt?></td>
                    <input id="newDebtInput" name="newDebt" value="<?=$oldDebt?>" type="hidden" />
                </tr> 
            </tbody>
        </table>
        <div id="addExpense" class="btn btn-primary no-print">Opret udlæg</div>
        <div id="fhExpenseWrapper">
            <?php
                if(count($fhAfregning->Udlæg->Post) > 0) {
                    ?>
                    <table id='fhInputTable' class='table table-bordered'> 
                        <thead>
                            <th>Dato</th>
                            <th>Hus</th>
                            <th>Indkøbssted</th>
                            <th>Beløb</th>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        foreach($fhAfregning->Udlæg->Post as $post) {
                            ?>
                            <tr class="expenceRow">
                                <td><input name="fhExDato_<?=$i?>"  value="<?=$post->Dato?>" max="<?=date('Y-m-d')?>" class="fhDatoInput" type="date"/></td>
                                <td><input name="fhExHouse_<?=$i?>"  value="<?=$post->Hus??0?>" class="fhHouseInput" type="number" min="1" max="39" step="2"/></td>
                                <td><input name="fhExPlace_<?=$i?>"  value="<?=$post->Indkøbssted?>" class="fhPlaceInput stringInput" type="text" /></td>
                                <td><input name="fhExpence_<?=$i?>" value="<?=$post->Beløb?>" class="fhExpenceInput" type="number" step="0.01" /></td>
                                <td><div class="btn btn-danger no-print" onclick="deletePost(this, 'tr')">Slet</div></td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                }
            ?>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $(".wrapperFH").click(function() {
            var expencesTotalInput = $("#expencesTotalInput");
            var expencesCell = $("#fhExpencesCell");        
            var expences = getSavedExpences();
            expencesTotalInput.attr("value", expences.toFixed(2));
            expencesCell.html(expences.toFixed(2));
        });

        $("#addExpense").click(function(){
            var rowCount = document.querySelectorAll('.expenceRow').length;
            var fhInputTable = document.getElementById("fhInputTable");
            var row = 
                "<tr class='expenceRow'><td><input name='fhExDato_" +  rowCount + "' class='fhDatoInput' type='date' max='<?=date('Y-m-d')?>'/></td>" + 
                "<td><input name='fhExHouse_" +  rowCount + "' val='1' class='fhHouseInput' type='number' min='1' max='39' step='2' /></td>" + 
                "<td><input name='fhExPlace_" +  rowCount + "' class='fhPlaceInput stringInput' type='text' /></td>" + 
                "<td><input name='fhExpence_" +  rowCount + "' val='0' class='fhExpenceInput' type='number' step='0.01' /></td>" + 
                "<td><div style='margin-right:10px' onclick='deletePost(this)' class='btn btn-danger no-print'>Slet</div>" +
                "<div onclick='setExpence(this)' class='btn btn-primary sm no-print'>Gem</div>" + 
                "</td></tr>";

            if(fhInputTable != null) {
                $("#fhInputTable tr:last").after(row);
                // $("#fhInputTable tr:last").find(".fhDatoInput").attr("name", "fhExDato_" + rowCount);
                // $("#fhInputTable tr:last").find(".fhPlaceInput").attr("name", "fhExPlace_" + rowCount);
                // $("#fhInputTable tr:last").find(".fhExpenceInput").attr("name", "fhExpence_" + rowCount);
            } else {
                $("#fhExpenseWrapper").append("<table id='fhInputTable' class='table table-bordered'>" + 
                "<thead><th>Dato</th><th>Hus</th><th>Indkøbssted</th><th>Beløb</th></thead><tbody>" + row + 
                "</tbody></table>");   
            }        
        });
    
        $("#payedInput").blur(function() {
            newDebt();
        });
    });

    function getSavedExpences() {
        var sum = 0;
        $(".fhExpenceInput").each(function(){
            sum += parseFloat($(this).val()); 
        });

        return sum;
    }

    function newDebt() {

        var expenceInput = $("#expencesTotalInput");
        var expenceCell = $("#fhExpencesCell");            
        var newDebtInput = $("#newDebtInput");
        var newDebtCell = $("#newDebtCell");

        var oldDebt = parseFloat($("#oldDebt").html());
        var payed = parseFloat($("#payedInput").val()) ?? 0;
        var expences = parseFloat(expenceInput.val()) ?? 0;

        var newDebt = oldDebt - payed + expences;

        $("#newDebtInput").attr("value", newDebt.toFixed(2));
        $("#newDebtCell").html(newDebt.toFixed(2));
    };

    /* onblur value update on button in expence table row */
    function setExpence(el){
        var expenceField = $(el).closest("tr").find(".fhExpenceInput");
        var dateField = $(el).closest("tr").find(".fhDatoInput");
        var houseField = $(el).closest("tr").find(".fhHouseInput");
        var placeField = $(el).closest("tr").find(".fhPlaceInput");

        var date = dateField.val();
        var place = placeField.val();
        var expence = parseFloat(expenceField.val()) ?? 0;

        if(expence == null || date == null || place == null || expence == "" || date == "" || place == "") {
            swal("Du skal udfylde alle felter", {
                icon: "info",
                button: true,
            });
        } else {
            var expencesTotalInput =  $("#expencesTotalInput");
            var expencesCell = $("#fhExpencesCell");

            var newExpence = getSavedExpences() ?? 0;

            expencesTotalInput.attr("value", newExpence.toFixed(2));
            expencesCell.html(newExpence.toFixed(2));

            $("#newDebtCell").trigger("click");
            // $(el).before("<div onclick='deletePost(this)' class='btn btn-danger no-print'>Slet</div>");
            $(el).css('display', 'none');
        }
    };

    function deletePost(el) {
        var expencesTotalInput = $("#expencesTotalInput");
        var expencesCell = $("#fhExpencesCell");        

        var deleteVal = $(el).closest("tr").find(".fhExpenceInput").val();
        var totalExpence = getSavedExpences();
        var newExpence = totalExpence - deleteVal;

        expencesTotalInput.attr("value", newExpence.toFixed(2));
        expencesCell.html(newExpence.toFixed(2));

        $(el).closest("tr").remove();
    }

</script>
