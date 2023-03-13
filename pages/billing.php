<div id="wrapper">
    <div class="space no-print"></div>   
    <img src="img/print.jpg" id="printBtn" class="print no-print" onclick="window.print()" title="Print siden">
    <h1 class="h frontHeader">Regnskab beboere</h1>
    <h5 class="center bottomline no-print">Husk at gemme tabellen for at aktualisere regningerne til beboerne</h5>
    <h6 class="center">Seneste regning er fra den <?=$currentFileDate?></h6>   
    <form id="form" onchange="updateCells()"  method="post" action="">
    <div id="clearTable" class="btn btn-primary genBtn no-print">
        <span class="pointer hovertexttop" data-hover="Fjerner de tal du lige har sat ind">Ryd input</span>
    </div>
    <div id="saveDataTop" onclick="saveData()" class="btn btn-primary genBtn no-print">
        <span class="pointer hovertexttop" data-hover="Gem data, print oversigtstabel og gør klar til print af regningerne">Gem</span>
    </div>
    <label class="no-print font20"><b>&nbsp;&nbsp;Betales senest d. </b></label>
    <input name="dueDate" type="date" placeholder="d-m-Y" min="<?=date('Y-m-d')?>" class=" no-print" id="dueDate" />
        <table id="table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">Hus</th>
                    <th scope="col">Navn</th>
                    <th scope="col">Efternavn</th>
                    <th scope="col" class="debt hovertext" data-hover="Hvis 'Gammel saldo' er negativ skylder madkassen beløbet til beboeren">Sidste Regning</th>
                    <th scope="col" class="payed hovertext" data-hover="Hvis madkassen har overført et bløb pga en negativ gæld indættes et negativt tal">Indbetalt<br></th>
                    <th scope="col" class="balance">Ny saldo</th>
                    <th scope="col">Voksne</th>
                    <th scope="col">Pubber</th>
                    <th scope="col">Børn</th>
                    <th scope="col">Spist for</th>
                    <th scope="col">Udlæg</th>
                    <th scope="col">Til Betaling<br><small>(Inkluderet <span id="surcharge"></span> DDK)</small ></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ( $houses as $house) {
                        $husNr = $house["Nr"];
                        ?>
                        <tr>
                            <td class="house"><?=$husNr?></td>
                            <input name="<?=$husNr.'_house'?>" class="input houseInput" value="<?=$husNr?>" type="hidden"/>
                            <td  class="person" ><?=$house->Navn?></td>
                            <input name="<?=$husNr.'_person'?>" class="personInput" value="<?=$house->Navn?>" type="hidden"/>
                            <td  class="person" ><?=$house->Efternavn?></td>
                            <input name="<?=$husNr.'_lastName'?>" class="lastName" value="<?=$house->Efternavn?>" type="hidden"/>
                            <td id="<?=$husNr.'_debt-cell'?>" class="debt"><?=checkVal($house->Udestående)?></td>
                            <input  id="<?=$husNr.'_debt-input'?>" name="<?=$husNr.'_debt'?>" class="input debtInput" value="<?=checkVal($house->Udestående)?>" type="hidden"/>
                            <td><input  id="<?=$husNr.'_payed-input'?>" name="<?=$husNr.'_payed'?>" class="input payed" value="0" type="number" step="0.01"/></td>
                            <td  id="<?=$husNr.'_balance-cell'?>" class="balance" value="0">0</td>
                            <input  id="<?=$husNr.'_balance-input'?>" name="<?=$husNr.'_balance'?>" class="input balanceInput" value="0" type="hidden"/>
                            <td><input  id="<?=$husNr.'_adult-input'?>" name="<?=$husNr.'_adult'?>" class="input adult" value="0" min="0" type="number"></td>
                            <td><input  id="<?=$husNr.'_teen-input'?>" name="<?=$husNr.'_teen'?>" class="input teen" value="0" min="0" type="number" ></td>
                            <td><input  id="<?=$husNr.'_child-input'?>" name="<?=$husNr.'_child'?>" class="input child" value="0" min="0" type="number"></td>
                            <td class="eaten">0</td>
                            <input  name="<?=$husNr.'_eaten'?>" class="input eatenInput" value="0" type="hidden"/>
                            <td><input  id="<?=$husNr.'_expenses-input'?>" name="<?=$husNr.'_expenses'?>" type="number" step="0.01" value="0" class="expenses"></td>
                            <td class="billing">40</td>
                            <input  name="<?=$husNr.'_billing'?>" class="input billingInput" value="0" type="hidden"/>
                        </tr> 
                <?php
                    }
                ?>
            </tbody>
        </table>
      </form>
      <?php
        $pathTmp= "xml/tenants/tmp/";
        $filesTmp = scandir($pathTmp, SCANDIR_SORT_DESCENDING);
        $filesTmp = array_diff($filesTmp, array('..', '.'));
      ?>
      <p class="center small">Aktuelle data er gemt i '<?=$filesTmp[0] ?? "" ?>'</p> 
    </div>