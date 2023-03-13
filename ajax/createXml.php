<?php
include("../session.php");
if (isset($_POST)) {

	$today = strtotime(date("Y/m/d"));
	$nextMonth = date('d/m/Y', strtotime('+1 month', $today));

	$dueDate = $_POST['dueDate'] != "" && $_POST['dueDate'] != null ? $_POST['dueDate'] : $nextMonth;
	$_SESSION['duedate'] = $dueDate;

	$data = array_slice($_POST, 1);
	// devide
	$houses = array_chunk($data, 12);

	$dom = new DOMDocument();

	$dom->encoding = 'utf-8';
	$dom->xmlVersion = '1.0';
	$dom->formatOutput = true;
	$xml_file_name = date("Y_m_d_h_i_s") . '.xml';

	$root = $dom->createElement('Regninger');
	$attr_due = new DOMAttr('Betalingsdato', $dueDate);

	$root->setAttributeNode($attr_due);

	foreach ($houses as $house) {
		$invoice_node = $dom->createElement('Hus');

		$attr_house_id = new DOMAttr('Nr', $house[0]);
		$invoice_node->setAttributeNode($attr_house_id);

		$child_node_name = $dom->createElement('Navn', $house[1]);
		$invoice_node->appendChild($child_node_name);

		$child_node_lname = $dom->createElement('Efternavn', $house[2]);
		$invoice_node->appendChild($child_node_lname);

		$child_node_debt = $dom->createElement('Udestående', $house[3]);
		$invoice_node->appendChild($child_node_debt);

		$child_node_payed = $dom->createElement('Indbetalt', ltrim($house[4], "0"));
		$invoice_node->appendChild($child_node_payed);

		$child_node_new_debt = $dom->createElement('Saldo', $house[5]);
		$invoice_node->appendChild($child_node_new_debt);

		$child_node_adults = $dom->createElement('Voksne', ltrim($house[6], "0"));
		$invoice_node->appendChild($child_node_adults);

		$child_node_teens = $dom->createElement('Pubber', ltrim($house[7], "0"));
		$invoice_node->appendChild($child_node_teens);

		$child_node_children = $dom->createElement('Børn', ltrim($house[8], "0"));
		$invoice_node->appendChild($child_node_children);

		$child_node_eaten = $dom->createElement('Spist', $house[9]);
		$invoice_node->appendChild($child_node_eaten);

		$child_node_expenses = $dom->createElement('Udlæg', ltrim($house[10], "0"));
		$invoice_node->appendChild($child_node_expenses);

		$child_node_billing = $dom->createElement('Regning', $house[11]);
		$invoice_node->appendChild($child_node_billing);

		// $child_node_deposit = $dom->createElement('Depositum', $house[12]);
		// $invoice_node->appendChild($child_node_deposit);

		$root->appendChild($invoice_node);
	}
	$dom->appendChild($root);


	//Move old files to archive
	// Get array of all source files
	
	$tmpFiles = scandir("../xml/tenants/tmp") ?? null;

	$source = "../xml/tenants/tmp/";
	$destination = "../xml/tenants/trash/";
	$delete = [];

	if ($tmpFiles) {
		foreach ($tmpFiles as $file) {
			if (in_array($file, array(".", "..")))
				continue;
			// If we copied this successfully, mark it for deletion
			if (copy($source . $file, $destination . $file)) {
				$delete[] = $source . $file;
			}
		}

		// Delete all successfully-copied files
		if (count($delete) > 0) {
			foreach ($delete as $file) {
				unlink($file);
			}
		}
	}

	// Save current file to xml folder
	if ($dom->save('../xml/tenants/tmp/' . $xml_file_name)) {
		$res = ["success" => 1, "xml" => $xml_file_name];
	} else {
		$res = ["success" => 2];
	}
} else {
		$res = ["success" => 1];
}

	echo json_encode($res);
 ?>