<?php
use Dompdf\Dompdf;

if(isset($_POST['submit_val']))
{
    $dompdf = new Dompdf();
    $options = $dompdf->getOptions();
    $options->setDefaultFont('Courier');
    $dompdf->setOptions($options);
    $dompdf->loadHtml("
        <table border=1 align=center width=400>.
        <tr><td>Name : </td><td>" . $_POST['name'] . "</td></tr>" .
        "<tr><td>Email : </td><td>" . $_POST['email'] . "</td></tr>" .
        "<tr><td>Age : </td><td>". $_POST['age'] . "</td></tr>" .
        "<tr><td>Country : </td><td>" . $_POST['country'] . "</td></tr>" .
        "</table>");
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("",array("Attachment" => false));
exit(0);
}
?>
<div id="wrapper" style="margin:0 auto;padding:0px;text-align:center;width:995px;">
    <div id="html_div">
        <form action="" method="post">
            <input type="text" name="name" placeholder="Enter Name">
            <br>
            <input type="text" name="email" placeholder="Enter Email">
            <br>
            <input type="text" name="age" placeholder="Enter Age">
            <br>
            <input type="text" name="country" placeholder="Enter Country">
            <br>
            <input type="submit" name="submit_val" value="GENERATE PDF">
        </form>
    </div>
</div>