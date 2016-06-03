<?php
if (isset($_FILES['upload-processSteps'])){
	$uploads_dir = '../uploads/';
	$checklist_dir = 'checklist/';
	$forms_dir = 'forms/';
	$processSteps_dir = 'process_steps/';

	$error = array();
	$fileName = $_FILES['upload-processSteps']['name'];
	$fileSize = $_FILES['upload-processSteps']['size'];
	$fileTmpName = $_FILES['upload-processSteps']['tmp_name'];
	$fileType = $_FILES['upload-processSteps']['type'];
	$fileName_exploded = explode('.', $fileName);
	$fileExt = strtolower(end($fileName_exploded));

	// Append timestamp to filename
	$timeStamp = date("YmdHis"); // 1/2/2016 1:05:12pm = 20160102130512
	$fileName = $fileName_exploded[0] . '_' . $timeStamp . '.' . $fileExt;

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
		move_uploaded_file($fileTmpName, $uploads_dir . $processSteps_dir . $fileName);
?>
		<div class="alert alert-success">
			<strong>Success!</strong> File "<?= $fileName ?>" was successfully uploaded.
		</div>
<?php
	} // End if upload success
}// End if file was posted to page
?>
