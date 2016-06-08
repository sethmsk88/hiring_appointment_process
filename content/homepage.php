<?php
	// Create complete path to a file determined by the parameters
	function getFileLink($category, $payPlan, &$fileNames_matrix)
	{
		$uploads_base_dir = "./uploads/";
		$uploads_category_dirs = array(
			'process_steps/',
			'checklist/',
			'forms/'
		);

		// Only return a link to the file if a fileName exists in the matrix
		if (is_null($fileNames_matrix[$category][$payPlan])){
			return '#';
		} else{
			return $uploads_base_dir . $uploads_category_dirs[$category] . $payPlan . '/' . $fileNames_matrix[$category][$payPlan];
		}
	}

	require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/apps/shared/db_connect.php';

	$categories = array(0,1,2);
	$payPlans = array('ap', 'exec', 'fac', 'ops', 'usps');
	$fileNames_matrix = array(); // category => array(payPlan => fileName)

	// Get most recently uploaded file for each Pay Plan and Category
	$sel_file_sql = "
		SELECT FileName
		FROM hrodt.hiring_appt_upload_history
		WHERE Category = ? AND PayPlan = ?
		ORDER BY UploadDate DESC
		LIMIT 1
	";

	if (!$stmt = $conn->prepare($sel_file_sql)){
		echo 'Prepare failed: (' . $conn->errno . ') ' . $conn->error . '<br />';
	} else{

		// Populate fileNames matrix
		foreach ($categories as $category){
			foreach ($payPlans as $payPlan){
				$stmt->bind_param("is", $category, $payPlan);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($fileName);
				$stmt->fetch();

				$fileNames_matrix[$category][$payPlan] = $fileName;
			}
		}
	}
?>

<div class="container">
	<div class="row">
		<div class="col-lg-9">
			<ul id="myTabs" class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#ops-tab" class="tab_title">OPS</a></li>
				<li><a data-toggle="tab" href="#usps-tab" class="tab_title">USPS</a></li>
				<li><a data-toggle="tab" href="#ap-tab" class="tab_title">A&amp;P</a></li>
				<li><a data-toggle="tab" href="#exec-tab" class="tab_title">Exec</a></li>
				<li><a data-toggle="tab" href="#fac-tab" class="tab_title">Fac</a></li>
			</ul>

			<div class="tab-content myTabs">
				<div id="ops-tab" class="tab-pane fade in active" style="line-height:2.75em;">
					<a
						href="<?= getFileLink(0, 'ops', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Process Steps
					</a><br />
					<a
						href="<?= getFileLink(2, 'ops', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Forms
					</a><br />
					<a
						href="<?= getFileLink(1, 'ops', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Checklist
					</a><br />
				</div>

				<div id="usps-tab" class="tab-pane fade" style="line-height:2.75em;">
					<a
						href="<?= getFileLink(0, 'usps', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Process Steps
					</a><br />
					<a
						href="<?= getFileLink(2, 'usps', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Forms
					</a><br />
					<a
						href="<?= getFileLink(1, 'usps', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Checklist
					</a><br />
				</div>

				<div id="ap-tab" class="tab-pane fade" style="line-height:2.75em;">
					<a
						href="<?= getFileLink(0, 'ap', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Process Steps
					</a><br />
					<a
						href="<?= getFileLink(2, 'ap', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Forms
					</a><br />
					<a
						href="<?= getFileLink(1, 'ap', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Checklist
					</a><br />
				</div>

				<div id="exec-tab" class="tab-pane fade" style="line-height:2.75em;">
					<a
						href="<?= getFileLink(0, 'exec', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Process Steps
					</a><br />
					<a
						href="<?= getFileLink(2, 'exec', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Forms
					</a><br />
					<a
						href="<?= getFileLink(1, 'exec', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Checklist
					</a><br />
				</div>

				<div id="fac-tab" class="tab-pane fade" style="line-height:2.75em;">
					<a
						href="<?= getFileLink(0, 'fac', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Process Steps
					</a><br />
					<a
						href="<?= getFileLink(2, 'fac', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Forms
					</a><br />
					<a
						href="<?= getFileLink(1, 'fac', $fileNames_matrix) ?>"
						target="_blank"
						class="btn btn-primary">
						Checklist
					</a><br />
				</div>
			</div>
		</div>
	</div>
</div>


