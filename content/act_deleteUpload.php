<?php
define("APP_PATH", "http://" . $_SERVER['HTTP_HOST'] . "/bootstrap/apps/hiring_appointment_process/");

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/apps/shared/db_connect.php';
require_once "../includes/functions.php";

// Start session or regenerate session id
sec_session_start();

// if the delete button was clicked
if (isset($_POST['fileID'])) {

	// Mark the file inactive
	$update_deactivate_sql = "
		UPDATE hrodt.hiring_appt_upload_history
		SET Active = 0
		WHERE ID = ?
	";
	if (!$stmt = $conn->prepare($update_deactivate_sql)){
		echo 'Prepare failed: (' . $conn->errno . ') ' . $conn->error . '<br />';
	} else{
		$stmt->bind_param("i", $_POST['fileID']);
		$stmt->execute();
	}

	$maxInactiveFiles = 10;

	// Test if more there are more than the max num of inactive files
	$sel_inactive_sql = "
		SELECT ID
		FROM hrodt.hiring_appt_upload_history
		WHERE Active = 0
		ORDER BY UploadDate ASC
	";
	if (!$stmt = $conn->prepare($sel_inactive_sql)){
		echo 'Prepare failed: (' . $conn->errno . ') ' . $conn->error . '<br />';
	} else{
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($inactive_fileID);

		if ($stmt->num_rows > $maxInactiveFiles) {

			// Delete oldest file from DB and server
			// TO DO
		}
	}

	// Redirect back to admin page
	header("Location: " . APP_PATH . "?page=admin");

	// This is part of my attempt to reload the DataTable with a JSON file
	// Output response, which is the updated table data in JSON format
	/*
	$tableData = array();

	$sel_all_sql = "
		SELECT LinkName, FileName, PayPlan, Category, UploadDate, UserID, ID
		FROM hrodt.hiring_appt_upload_history
		WHERE Active = 1
		ORDER BY UploadDate ASC
	";
	if (!$stmt = $conn->prepare($sel_all_sql)){
		echo 'Prepare failed: (' . $conn->errno . ') ' . $conn->error . '<br />';
	} else {
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			$tableData[] = $row;
		}
	}

	echo json_encode($tableData);*/
}
?>
