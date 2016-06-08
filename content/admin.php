<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/apps/shared/db_connect.php';

	$categories = array(0,1,2);
	$mostRecentUploads = array(); // category => array(uploadDate, fullName)

	// Get most recent upload information for each category
	$sel_recent_upload_sql = "
		SELECT h.UploadDate, u.firstName, u.lastName
		FROM hrodt.hiring_appt_upload_history h
		JOIN secure_login.users u
		ON h.UserID = u.id
		WHERE h.Category = ?
		ORDER BY h.UploadDate DESC
		LIMIT 1
	";
	if (!$stmt = $conn->prepare($sel_recent_upload_sql)){
		echo 'Prepare failed: (' . $conn->errno . ') ' . $conn->error . '<br />';
	} else{

		// Get the most recent upload information for each category
		foreach ($categories as $category) {
			$stmt->bind_param("i", $category);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($uploadDate, $firstName, $lastName);
			$stmt->fetch();

			$fullName = $firstName . ' ' . $lastName;
			$mostRecentUploads[$category] = array($uploadDate, $fullName);
		}
	}
?>

<script src="./js/admin.js"></script>

<div class="container">

	<div class="row">
		<div class="col-lg-12">
			<h3>Update Files</h3>
			<span class="light-text">Select the Pay Plan for which you would like to upload files.</span>
		</div>
	</div>
	<br />

	<form
		name="uploadFile-form"
		id="uploadFile-form"
		role="form"
		method="post"
		action=""
		enctype="multipart/form-data">

		<div class="row">
			<div class="col-lg-4 form-group">
				<label for="payPlan">Pay Plan</label>
				<select name="payPlan" id="payPlan" class="form-control">
					<option value="-1"></option>
					<option value="0">A&amp;P</option>
					<option value="1">Exec</option>
					<option value="2">Fac</option>
					<option value="3">OPS</option>
					<option value="4">USPS</option>
				</select>
			</div>
		</div>

		<div id="ajax_uploadResponse">
			<!-- To be filled by AJAX -->
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-lg-5">
					<label for="upload-processSteps">Process Steps</label><br />
					<div class="input-group">
						<span class="input-group-btn">
							<span class="btn btn-primary btn-file">
								Browse <input type="file" name="upload-processSteps" id="upload-processSteps">
							</span>
						</span>
						<input type="text" class="form-control" readonly="readonly">
						<span class="input-group-btn">
							<span class="btn btn-success upload-btn">
								<span class="glyphicon glyphicon-upload"></span>
								Upload
							</span>
						</span>
					</div>
				</div>
			</div>

			<div class="row">
				<div id="processSteps-updated" class="col-lg-8 light-text">
					<?php
						// If there is at least one upload in the database,
						// then display last updated info
						if (strlen($mostRecentUploads[0][0]) !== 0){
							echo 'Last updated: ' . date('n/j/Y g:ia', strtotime($mostRecentUploads[0][0])) . ' by ' . $mostRecentUploads[0][1];
						}						
					?>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-lg-5">
					<label for="upload-checklist">Checklist</label><br />
					<div class="input-group">
						<span class="input-group-btn">
							<span class="btn btn-primary btn-file">
								Browse <input type="file" name="upload-checklist" id="upload-checklist">
							</span>
						</span>
						<input type="text" class="form-control" readonly="readonly">
						<span class="input-group-btn">
							<span class="btn btn-success upload-btn">
								<span class="glyphicon glyphicon-upload"></span>
								Upload
							</span>
						</span>
					</div>
				</div>
			</div>		

			<div class="row">
				<div id="checklist-updated" class="col-lg-8 light-text">
					<?php
						// If there is at least one upload in the database,
						// then display last updated info
						if (strlen($mostRecentUploads[1][0]) !== 0){
							echo 'Last updated: ' . date('n/j/Y g:ia', strtotime($mostRecentUploads[1][0])) . ' by ' . $mostRecentUploads[1][1];
						}						
					?>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-lg-5">
					<label for="upload-formsPacket">Forms Packet</label><br />
					<div class="input-group">
						<span class="input-group-btn">
							<span class="btn btn-primary btn-file">
								Browse <input type="file" name="upload-formsPacket" id="upload-formsPacket">
							</span>
						</span>
						<input type="text" class="form-control" readonly="readonly">
						<span class="input-group-btn">
							<span class="btn btn-success upload-btn">
								<span class="glyphicon glyphicon-upload"></span>
								Upload
							</span>
						</span>
					</div>
				</div>
			</div>

			<div class="row">
				<div id="formsPacket-updated" class="col-lg-8 light-text">
					<?php
						// If there is at least one upload in the database,
						// then display last updated info
						if (strlen($mostRecentUploads[2][0]) !== 0){
							echo 'Last updated: ' . date('n/j/Y g:ia', strtotime($mostRecentUploads[2][0])) . ' by ' . $mostRecentUploads[2][1];
						}						
					?>
				</div>
			</div>
		</div>
	</form>
</div>
