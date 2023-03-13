<?php
    include 'xmlHandlers/getFiles.php';

    $tenantPath = "xml/tenants/";
    $fhPath = "xml/fhXml/";
?>
<div class="wrapper" >
    <h1 class="h">Arkiv</h1>
    <br/><br/>
        <h3>Tilbagesæt seneste regnskab.</h3>
        <h4  class="text-muted">Sæt regnskabsgrundlaget tilbage til seneste afregning</h4>
        <div class="row">
            <div class="col-6">
                <button onclick="resetInvoice(this)" id="resetTenantBill" bill="tenants" class="btn btn-primary btn-lg w-100">Tilbageset Beboerregnskab</button>
            </div>
            <div class="col-6">
                <button onclick="resetInvoice(this)" id="resetFhBill" bill="fhXml" class="btn btn-primary btn-lg w-100">Tilbageset Fælleshusregnskab</button>
            </div>
        </div>      
    <br/><br/>

    <h3>Se gamle regnskaber</h3>
        <h4  class="text-muted">Du kan læse de seneste 15 regskaber for hhv Beboere og fælleshuset</h4>
        <div class="row">
            <div class="col-6">
                <button  onclick="toggleElement('#tenantArchiveList')" class="btn btn-primary btn-lg w-100">Tilbageset Beboerregnskab</button>
                <ul class="fileList" id="tenantArchiveList">
                    <?php
                    $files = getArchiveFiles(false) ?? [];
                    foreach($files as $file) {
                        ?>
                        <li class="archive_li list-group-item" source="tenants"  id="<?=$GLOBALS['tenantPath'] .'previous/'.$file?>"><?=filenameToDate($file)?>&nbsp;&nbsp;<small>&nbsp;[source: <?=$file?>]</small></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="col-6">
                <button onclick="toggleElement('#fhArchiveList')" class="btn btn-primary btn-lg w-100">Tilbageset Fælleshusregnskab</button>
                <ul class="fileList" id="fhArchiveList">
                <?php
                $files = getArchiveFiles() ?? [];
                foreach($files as $file) {
                    ?>
                    <li class="archive_li list-group-item" source="fh" id="<?=$GLOBALS['fhPath'] .'previous/'.$file?>"><?=filenameToDate($file)?>&nbsp;&nbsp;<small>&nbsp;[source: <?=$file?>]</small></li>
                    <?php
                }
                ?>
            </ul>
            </div>
        </div>
    <div id="archiveBox" class="box">
        <div class="container">
            <table id="archiveTabl" class="table table-bordered table-striped table-hover">
                <thead></thead>
                <tbody></tbody>
            </table>
            <div id="x" onclick=closeBox(this)>x</div>
        </div>
    </div>
</div>
<script>
    /**
     * @param xml xhttp response data
     */
    function displayXml(xml, source) {
        
        xmlDoc = xml.responseXML;
        var tblContent = "";

        if(source == "tenants") {
            var x = xmlDoc.getElementsByTagName('Hus');
            for (i = 0; i < x.length; i++) {
                if( i == 0) {
                    var tblHead = "<th>Nr</th>";
                    for (const child of x[i].children) {
                        tblHead += "<th>" + child.tagName + "</th>";
                    }
                } 

                if ( x[i].getAttribute('Nr') != null &&  x[i].getAttribute('Nr') != "") {
                    tblContent += "<tr><td>" + x[i].getAttribute('Nr') + "</td>";
                }

                var house =  x[i].children
                for (const houseChild of house) {
                    tblContent += "<td>" + houseChild.textContent + "</td>";
                }
                tblContent += "<tr>";
            }
        } else if (source == "fh") {
            var tblPosts = "";
            var x = xmlDoc.getElementsByTagName('Fælleshusregning');   
            xPosts = xmlDoc.getElementsByTagName('Post'); 

            tblContent += "<tr><th>Betalingsdato</th><td>" + x[0].getAttribute('Betalingsdato') +"</td><td></td></tr>";
            for (const child of x[0].children) {
                if(child.tagName == "Udlæg") {
                    var posts = x[0].getElementsByTagName('Post');
                    if(posts.length > 0 ) {
                       tblContent += "<tr><th id='exp'>Udlæg:</th></tr>";
                    }
                } else {
                    tblContent += "<tr><th>" + child.tagName + "</th><td>" + child.textContent + "</td><td></td></tr>";
                }
            }
            
            // nested table for expence posts
            if(xPosts[0].children.length > 0) {
                var postTbl = "<table class='table table-sm table-hover'>";
                var tblPosts = "";
                var tblPostsHead = "<thead><tr>";

                for(var i = 0; i < xPosts.length; i++) {
                    tblPosts += "<tr>";

                    for (const post of xPosts[i].children) {
                        if(i==0) {
                            tblPostsHead += "<th>" + post.tagName + "</th>";
                        } 
                        tblPosts += "<td>" + post.textContent +"</td>";
                    }
                    tblPosts += "</tr>";
                }
                tblPostsHead += "</tr></thead>";
                postTbl += "</table>";
            }
        }
         
        $("#archiveTabl > thead").append(tblHead);
        $("#archiveTabl > tbody").append(tblContent);
        $("#postTbl > thead > tr").append(tblPostsHead);
        $("#exp").append(postTbl);
        $("#exp > table").append(tblPostsHead);
        $("#exp > table > thead").append(tblPosts);
        $("#archiveBox").css("display", "block");
    }   
    
    /* Document ready */
    $(document).ready(function() {
        $(".archive_li").click(function() {
            var xml = $(this).attr("id");
            var source = $(this).attr("source");
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    displayXml(this, source);
                }
            };
            xhttp.open("GET", xml, true);
            xhttp.send();   
        });
    });

    /**
     * replaces current billing basis to last bill
     */
    function resetInvoice(el) {
        swal({
            title: "Er du sikker?",
            text: "Det seneste regnskab bliver slettet.",
            icon: "info",
            buttons: ["Slet ikke", "Ja, slet seneste regnskab"],
        }).then((redirect) => {
            if(redirect) {               
                var folder = $(el).attr("bill");        
                $.post("ajax/resetBill.php", {"folder" : folder}, function(response) {
                    if(response == 1) {
                        swal({
                            title: "Regnskabsgrundlaget er sat tilbage til seneste afregning",
                            icon: "success",
                            buttons: "OK",
                        });
                    }
                })
            }        
        });     

    }
</script>