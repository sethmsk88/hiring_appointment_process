<?php
define("APP_PATH", "http://" . $_SERVER['HTTP_HOST'] . "/bootstrap/apps/hiring_appointment_process/");

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/apps/shared/db_connect.php';
require_once "../includes/functions.php";

// Start session or regenerate session id
sec_session_start();

// if the edit submit button was clicked
if (isset($_POST['edit-fileID'])) {
	$update_file_sql = "
		UPDATE hrodt.hiring_appt_upload_history
		SET LinkName = ?,
			PayPlan = ?,
			Category = ?
		WHERE ID = ?
	";

	if (!$stmt = $conn->prepare($update_file_sql)){
		echo 'Prepare failed: (' . $conn->errno . ') ' . $conn->error . '<br />';
	} else{
		$stmt->bind_param("ssii", $_POST['edit-linkName'], $_POST['edit-payPlan'],
			$_POST['edit-category'], $_POST['edit-fileID']);
		$stmt->execute();
	}
}

// Redirect back to admin page
header("Location: " . APP_PATH . "?page=admin");
?>
