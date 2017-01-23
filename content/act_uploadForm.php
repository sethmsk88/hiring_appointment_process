<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/apps/shared/db_connect.php';
require_once "../includes/functions.php";

// Set timezone
date_default_timezone_set('America/New_York');

// Start session or regenerate session id
sec_session_start();

$json_response = array();

$upload_base_dir = '../uploads/';
$upload_ids = array(
	0 => "upload-processSteps",
	1 => "upload-checklist",
	2 => "upload-formsPacket"
);
$upload_type_dirs = array(
	0 => "process_steps/",
	1 => "checklist/",
	2 => "forms/"
);
$upload_payPlan_dirs = array(
	0 => "ap/",
	1 => "exec/",
	2 => "fac/",
	3 => "ops/",
	4 => "usps/"
);

// Determine the subdirectory in which to put the file
if (strlen($_FILES['upload-processSteps']['name']) > 0){
	$uploadType = 0;
} else if (strlen($_FILES['upload-checklist']['name']) > 0){
	$uploadType = 1;
} else if (strlen($_FILES['upload-formsPacket']['name']) > 0){
	$uploadType = 2;
} else{
	$uploadType = -1;
}

// These values line up with the uploadTypes above
$response_ids = array(
	0 => "processSteps-updated",
	1 => "checklist-updated",
	2 => "formsPacket-updated"
);
$linkName_inputs = array(
	0 => "linkName-processSteps",
	1 => "linkName-checklist",
	2 => "linkName-forms"
);

// If required values were posted to this page
if ($uploadType > -1 && isset($_POST['payPlan'])){

	$errors = array();
	$fileName = $_FILES[$upload_ids[$uploadType]]['name'];
	$fileSize = $_FILES[$upload_ids[$uploadType]]['size'];
	$fileTmpName = $_FILES[$upload_ids[$uploadType]]['tmp_name'];
	$fileType = $_FILES[$upload_ids[$uploadType]]['type'];
	$fileName_exploded = explode('.', $fileName);
	$fileExt = strtolower(end($fileName_exploded));

	$fileName = make_unique_filename($fileName_exploded[0] . '.' . $fileExt, 'uploads/');

	// Check to see if extension is valid
	$extensions = array("pdf", "zip");
	if (in_array($fileExt, $extensions) === false){
		array_push($errors, "Invalid file type. Please choose a PDF or ZIP file.");
	}

	// Make sure file is not too large
	$maxFileSize = 1024 * 1024 * 64; // 64MB
	if ($fileSize > $maxFileSize){
		array_push($errors, "Max file size exceeded. File size must be less than 64MB");
	}

	// Make sure filename is not too long
	$maxFileNameLength = 250;
	if (strlen($fileName) > $maxFileNameLength){
		array_push($errors, "Filename is too long (max 250 characters)");
	}

	// Make sure a pay plan was selected
	if ($_POST['payPlan'] == -1){
		array_push($errors, "A Pay Plan must be selected");
	}

	// If upload failed display error message
	if (empty($errors) == false){
		$json_response['errors'] = '<div class="alert alert-danger"><strong>Error!</strong> File was NOT uploaded<br />';

		foreach ($errors as $errorMsg) {
			$json_response['errors'] .= $errorMsg . '<br />';
		}
		$json_response['errors'] .= '</div>';

	} else{ // Else, there are no errors so upload file
		
		// Create upload path
		$uploadPath = $upload_base_dir . $upload_type_dirs[$uploadType] . $upload_payPlan_dirs[$_POST['payPlan']] . $fileName;

		move_uploaded_file($fileTmpName, $uploadPath);

		$payPlan_numeric = convertPayPlan($_POST['payPlan'], 'numeric');

		$linkName = $_POST[$linkName_inputs[$uploadType]];
		
		// Insert History
		$insert_uploadHistory_sql = "
			INSERT INTO hrodt.hiring_appt_upload_history (UploadDate, FileName, LinkName, PayPlan, Category, UserID)
			VALUES (NOW(),?,?,?,?,?)
		";
		if (!$stmt = $conn->prepare($insert_uploadHistory_sql)) {
			$json_response['errors'] = 'Prepare failed: (' . $conn->errno . ') ' . $conn->error . '<br />';
		} else if (!$stmt->bind_param("sssii",
			$fileName, $linkName, $payPlan_numeric, $uploadType, $_SESSION['user_id'])){
			$json_response['errors'] = 'Binding parameters failed (' . $stmt->errno . ') ' . $stmt->error . '<br />';
		} else if (!$stmt->execute()) {
			$json_response['errors'] = 'Execute failed: (' . $stmt->errno . ') ' . $stmt->error . '<br />';
		}

		// Append success message to response
		$json_response['success_msg'] = '<div class="alert alert-success"><strong>Success!</strong> File "' . $fileName . '" was successfully uploaded.</div>';

	} // End if upload success
} else { // End if file was posted to page
	$json_response['errors'] = '<div class="alert alert-danger"><strong>Error!</strong> File was NOT uploaded<br />A File must be selected</div>';
}
echo json_encode($json_response);
?>
