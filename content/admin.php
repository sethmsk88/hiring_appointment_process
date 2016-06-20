<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/apps/shared/db_connect.php';

	require_once './includes/delete_confirm.php'; // confirm modal

	$categories = array(0,1,2);
	$mostRecentUploads = array(); // category => array(uploadDate, fullName)

	// Get most recent upload information for each category
	$sel_recent_upload_q1 = "
		SELECT h.UploadDate, u.firstName, u.lastName
		FROM hrodt.hiring_appt_upload_history h
		JOIN secure_login.users u
		ON h.UserID = u.id
		WHERE h.Category = ?
		ORDER BY h.UploadDate DESC
		LIMIT 1
	";
	if (!$stmt = $conn->prepare($sel_recent_upload_q1)){
		echo 'Prepare failed: (' . $conn->errno . ') ' . $conn->error . '<br />';
	} else{

		// Get the most recent upload information for each category
		foreach ($categories as $category) {
			$stmt->bind_param("i", $category);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($q1_uploadDate, $q1_firstName, $q1_lastName);
			$stmt->fetch();

			$fullName = $q1_firstName . ' ' . $q1_lastName;
			$mostRecentUploads[$category] = array($q1_uploadDate, $fullName);
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

	<div class="row">
		<div class="col-lg-12 section-divider"></div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<h3>Delete Files</h3>
		</div>
	</div>


<?php
	// Get all active uploaded files
	$sel_all_uploads_q2 = "
		SELECT h.ID, h.UploadDate, h.FileName, h.LinkName, h.PayPlan,
			h.Category, u.firstName, u.lastName
		FROM hrodt.hiring_appt_upload_history h
		JOIN secure_login.users u
		ON h.UserID = u.id
		WHERE h.Active = 1
		ORDER BY h.UploadDate DESC
	";
	if (!$stmt = $conn->prepare($sel_all_uploads_q2)){
		echo 'Prepare failed: (' . $conn->errno . ') ' . $conn->error . '<br />';
	} else{
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($q2_fileID, $q2_uploadDate, $q2_fileName, $q2_linkName, $q2_payPlan, $q2_category, $q2_firstName, $q2_lastName);
	}
?>

	<form
		name="editUpload-form"
		id="editUpload-form"
		role="form"
		method="post"
		action="./content/act_deleteUpload.php">

		<input type="hidden" name="fileID" id="fileID" value="" />

		<div class="row">
			<table id="uploadedFiles-table" class="table table-striped">
				<thead>
					<tr>
						<th>Link Name</th>
						<th>Filename</th>
						<th>Pay Plan</th>
						<th>Category</th>
						<th>Upload Date</th>
						<th>Uploaded By</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						// For each uploaded active file
						while ($stmt->fetch()) {
							$fileName_edited = preg_replace("/_\d+.pdf$/", "", $q2_fileName) . ".pdf"; 
					?>
					<tr>
						<td><?= $q2_linkName ?></td>
						<td><?= $fileName_edited ?></td>
						<td><?= convertPayPlan($q2_payPlan, "pay_levels") ?></td>
						<td><?= convertCategory($q2_category) ?></td>
						<td><?= date('n/j/Y', strtotime($q2_uploadDate)) ?></td>
						<td><?= $q2_firstName . ' ' . $q2_lastName ?></td>
						<td>
							<button
								type="button"
								id="file_<?= $q2_fileID ?>"
								class="btn btn-danger delete-btn"
								data-toggle="modal"
								data-target="#confirmDelete"
								data-title="Delete File"
								data-message="Are you sure you want to delete this file?<br /><b><?= $fileName_edited ?></b>">

								<span class="glyphicon glyphicon-trash"></span> Delete
							</button>
						</td>
					</tr>
					<?php
						}
					?>
					
				</tbody>
			</table>
		</div>
	</form>
</div>
