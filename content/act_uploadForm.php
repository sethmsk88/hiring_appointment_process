<?php

$upload_base_dir = '../uploads/';
$upload_ids = array(
	0 => "upload-processSteps",
	1 => "upload-checklist",
	2 => "upload-formPacket"
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

// Determine which subdirectory to put the file in 
if (isset($_FILES['upload-processSteps'])){
	$uploadType = 0;
} else if (isset($_FILES['upload-checklist'])){
	$uploadType = 1;
} else if (isset($_FILES['upload-formPacket'])){
	$uploadType = 2;
} else{
	$uploadType = -1;
}

/*echo 'uploadType: ' . $uploadType . '<br />';
echo 'payPlan: ' . $_POST['payPlan'] . '<br />';

exit;*/
// If required values were posted to this page
if ($uploadType > -1 && isset($_POST['payPlan'])){

	$error = array();
	$fileName = $_FILES[$upload_ids[$uploadType]]['name'];
	$fileSize = $_FILES[$upload_ids[$uploadType]]['size'];
	$fileTmpName = $_FILES[$upload_ids[$uploadType]]['tmp_name'];
	$fileType = $_FILES[$upload_ids[$uploadType]]['type'];
	$fileName_exploded = explode('.', $fileName);
	$fileExt = strtolower(end($fileName_exploded));

	// Append timestamp to filename
	$timeStamp = date("YmdHis"); // 1/2/2016 1:05:12pm = 20160102130512
	$fileName = $fileName_exploded[0] . '_' . $timeStamp . '.' . $fileExt;

	// Create upload path
	$uploadPath = $upload_base_dir . $upload_type_dirs[$uploadType] . $upload_payPlan_dirs[$_POST['payPlan']] . $fileName;

	// Check to see if extension is valid
	$extensions = array("pdf");
	if (in_array($fileExt, $extensions) === false) {
		$errors[] = "Invalid file type. Please choose a PDF file.";
	}

	// Make sure file is not too large
	$maxFileSize = 1024 * 1024 * 64; // 64MB
	if ($fileSize > $maxFileSize) {
		$errors[] = "Max file size exceeded. File size must be less than 64MB";
	}

	// Make sure filename is not too long
	$maxFileNameLength = 250;
	if (strlen($fileName) > $maxFileNameLength) {
		$errors[] = "File name is too long (max 250 characters)";
	}

	// If upload failed display error message
	if (empty($errors) == false) {
		echo '<div class="alert alert-danger"><strong>Error!</strong> File was NOT uploaded<br />';
		foreach ($errors as $errorMsg) {
			echo $errorMsg . '<br />';
		}
		echo '</div>';
	} else{ // Else, there are no errors so upload file
		move_uploaded_file($fileTmpName, $uploadPath);
?>
		<div class="alert alert-success">
			<strong>Success!</strong> File "<?= $fileName ?>" was successfully uploaded.
		</div>
<?php
	} // End if upload success
}// End if file was posted to page
?>
