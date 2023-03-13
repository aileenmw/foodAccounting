<?php
include("../session.php");

if (isset($_POST)) {

    $i = 0;
    $posts = []; 
    foreach($_POST as $key => $val) {

        if(strpos($key, "fhEx") !== false) {
            switch (true) {
                case strpos($key, "fhExDato_") !== false :
                    $posts[$i]["date"] = $val;
                break;
				case strpos($key, "fhExHouse_") !== false :
                    $posts[$i]["house"] = $val ?? 0;
                break;
                case strpos($key, "fhExPlace_") !== false :
                    $posts[$i]["place"] = $val; 
                break;
                case strpos($key, "fhExpence_") !== false : 
                    $posts[$i]["amount"] = $val;
                    $i++;
                break;
            }
        }
    }

	$today = strtotime(date("Y/m/d"));
	$nextMonth = date('d/m/Y', strtotime('+1 month', $today));

    // TODO : duedate must be session so it can be acccessed globally
	$dueDate = $_SESSION['duedate'] ?? $nextMonth;

	$dom = new DOMDocument();

	$dom->encoding = 'utf-8';
	$dom->xmlVersion = '1.0';
	$dom->formatOutput = true;
	$xml_file_name = date("Y_m_d_h_i_s") . '.xml';

	$root = $dom->createElement('Fælleshusregning');
	$attr_due = new DOMAttr('Betalingsdato', $dueDate);
	$root->setAttributeNode($attr_due);

	    // $invoice_node = $dom->createElement('Afregning');

		$child_node_debt = $dom->createElement('Udestående', $_POST['oldDebt'] ?? 0);
		$root->appendChild($child_node_debt);

		$child_node_payed = $dom->createElement('Indbetalt', $_POST['payed'] ?? 0);
		$root->appendChild($child_node_payed);

        $child_node_expenses = $dom->createElement('Udlæg');

        $attr_totalExp = new DOMAttr('Total', $_POST['expencesTotal']);
	    $child_node_expenses->setAttributeNode($attr_totalExp);

        foreach($posts as $post) {

            $child_node_expensePost = $dom->createElement('Post');
            
            $child_node_exDate = $dom->createElement('Dato', $post['date']);
            $child_node_expensePost->appendChild($child_node_exDate);

			$child_node_exHouse = $dom->createElement('Hus', $post['house']);
            $child_node_expensePost->appendChild($child_node_exHouse);

            $child_node_exPlace = $dom->createElement('Indkøbssted', $post['place']);
            $child_node_expensePost->appendChild($child_node_exPlace);

            $child_node_exAmount = $dom->createElement('Beløb', $post['amount']);
            $child_node_expensePost->appendChild($child_node_exAmount);

            $child_node_expenses->appendChild($child_node_expensePost);
        }

        $root->appendChild($child_node_expenses);

        $child_node_billing = $dom->createElement('Regning', $_POST['newDebt']);
        $root->appendChild($child_node_billing);

        // $root->appendChild($root);
        $dom->appendChild($root);


	//Move old files to archive
	// Get array of all source files
	$files = scandir("../xml/fhXml/tmp");
	$source = "../xml/fhXml/tmp/";
	$destination = "../xml/fhXml/trash/";
	$delete = [];

	//if (count($files) > 0) {
	foreach ($files as $file) {
		if (in_array($file, array(".", "..")))
			continue;
		// If we copied this successfully, mark it for deletion
		if (copy($source . $file, $destination . $file)) {
			$delete[] = $source . $file;
			// cleanup
		$currentFiles = glob("../xml/fhxml/current/*"); 
		foreach($currentFiles as $cur){ 
			if(is_file($cur)) {
				unlink($cur); 
			}
}  
		}
	}

	// Delete all successfully-copied files
	if (count($delete) > 0) {
		foreach ($delete as $file) {
			unlink($file);
		}
	}

	// Save current data to tmp and curr file to xml folder
	if ($dom->save('../xml/fhXml/tmp/' . $xml_file_name)) {
		if($dom->save('../xml/fhXml/current/' . $xml_file_name)) {
			$res= ["success" => 1, "xml" => $xml_file_name ];
		}
		$_SESSION['fhXml'] = $xml_file_name;
	} else {
		$res= ["success" => 2, "xml" => ""];
	}
} else {
		$res= ["success" => 0, "xml" => ""];
}

echo json_encode($res);

 ?>