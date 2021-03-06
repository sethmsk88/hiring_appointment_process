<script src="./js/homepage.js"></script>

<?php
	// Create complete paths to files determined by the parameters
	// Return array of links
	function getFileLinks($category, $payPlan, &$fileNames_matrix)
	{
		$uploads_base_dir = "./uploads/";
		$uploads_category_dirs = array(
			'process_steps/',
			'checklist/',
			'forms/'
		);

		$links = array();

		// Only return a link to the file if a fileName exists in the matrix
		if (count($fileNames_matrix[$category][$payPlan]) !== 0){			

			// Add each link to links array
			foreach ($fileNames_matrix[$category][$payPlan] as $fileInfo){
				// Get link name
				$links[$fileInfo['linkName']] = $uploads_base_dir . $uploads_category_dirs[$category] . $payPlan . '/' . $fileInfo['fileName'];

				// $linkName = preg_replace("/_\d+.pdf$/", "", $fileName); // remove digits from end of fileName
			}
		}
		return $links;
	}

	require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/apps/shared/db_connect.php';

	$categories = array(0,1,2); // 0 = Process Steps, 1 = Checklist, 2 = Forms
	$payPlans = array('ap', 'exec', 'fac', 'ops', 'usps');
	$fileNames_matrix = array(); // category => array(payPlan => array(linkName => fileName))

	// Get Active uploaded files for each Pay Plan and Category
	$sel_files_sql = "
		SELECT FileName, LinkName
		FROM hrodt.hiring_appt_upload_history
		WHERE Category = ?
			AND PayPlan = ?
			AND Active = 1
	";

	if (!$stmt = $conn->prepare($sel_files_sql)){
		echo 'Prepare failed: (' . $conn->errno . ') ' . $conn->error . '<br />';
	} else{

		// Populate fileNames matrix
		// ($category =>
		//		($payPlan =>
		//			('fileName'=>$fileName, 'linkName'=>$linkName)
		//		)
		// )
		foreach ($categories as $category){
			foreach ($payPlans as $payPlan){
				$stmt->bind_param("is", $category, $payPlan);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($fileName, $linkName);
				
				$fileNames_matrix[$category][$payPlan] = array(); // initialize array for fileNames

				// Add each file for this payPlan and category to the matrix
				while ($stmt->fetch()){

					// if linkName is blank, make linkName = shortened fileName
					if (strlen($linkName) === 0)
						$linkName = $fileName;

					array_push($fileNames_matrix[$category][$payPlan], array('fileName' => $fileName, 'linkName' => $linkName));
				}
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
				<?php if ($loggedIn) { // INTERNAL ONLY ?>
					<li><a data-toggle="tab" href="#exec-tab" class="tab_title">Exec</a></li>
					<li><a data-toggle="tab" href="#fac-tab" class="tab_title">Fac</a></li>
				<?php } ?>
			</ul>

			<div class="tab-content myTabs">

				<!-- OPS Tab -->
				<div id="ops-tab" class="tab-pane fade in active">
			
					<!-- Process Steps -->
					<!-- HIDDEN TEMPORARILY at the request of Terissa Brown -->
					<div class="row hidden">
						<div class="col-sm-7 col-md-6">
							<div id="collapse-group-0" class="panel-group">
								<div class="panel">
									<div class="panel-heading">
										<a
											data-toggle="collapse"
											data-parent="#collapse-group-0"
											href="#collapse-0">
											Process Steps
										</a>
									</div>
									<div id="collapse-0" class="panel-collapse collapse">
										<div class="panel-body">
											<?php
												// Create a link for each file in this category and pay plan
												$links = getFileLinks(0, 'ops', $fileNames_matrix);
												$popover_content = "";

												foreach ($links as $linkName => $linkPath){
													echo '<a href="' . $linkPath . '" target="_blank">' . $linkName . '</a><br />';
												}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<!-- Forms -->
					<div class="row">
						<div class="col-sm-7 col-md-6">
							<div id="collapse-group-1" class="panel-group">
								<div class="panel">
									<div class="panel-heading">
									<!--
										<a
											data-toggle="collapse"
											data-parent="#collapse-group-1"
											href="#collapse-1">
											Forms
										</a>
									-->
									<a href="http://www.famu.edu/index.cfm?hr&Forms" target="_blank">Forms</a>
									</div>
									<div id="collapse-1" class="panel-collapse collapse">
										<div class="panel-body">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<!-- Checklist -->
					<div class="row">
						<div class="col-sm-7 col-md-6">
							<div id="collapse-group-2" class="panel-group">
								<div class="panel">
									<div class="panel-heading">
										<a
											data-toggle="collapse"
											data-parent="#collapse-group-2"
											href="#collapse-2">
											Checklist
										</a>
									</div>
									<div id="collapse-2" class="panel-collapse collapse">
										<div class="panel-body">
											<a href="http://www.famu.edu/index.cfm?hr&Forms#N" target="_blank">New Employee Sign-Up Checklist</a><br />
											<?php
												// Create a link for each file in this category and pay plan
												$links = getFileLinks(1, 'ops', $fileNames_matrix);
												$popover_content = "";

												foreach ($links as $linkName => $linkPath){
													echo '<a href="' . $linkPath . '" target="_blank">' . $linkName . '</a><br />';
												}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- USPS Tab -->
				<div id="usps-tab" class="tab-pane fade">

					<!-- Process Steps -->
					<div class="row">
						<div class="col-sm-7 col-md-6">
							<div id="collapse-group-3" class="panel-group">
								<div class="panel">
									<div class="panel-heading">
										<a
											data-toggle="collapse"
											data-parent="#collapse-group-3"
											href="#collapse-3">
											Process Steps
										</a>
									</div>
									<div id="collapse-3" class="panel-collapse collapse">
										<div class="panel-body">
											<?php
												// Create a link for each file in this category and pay plan
												$links = getFileLinks(0, 'usps', $fileNames_matrix);
												$popover_content = "";

												foreach ($links as $linkName => $linkPath){
													echo '<a href="' . $linkPath . '" target="_blank">' . $linkName . '</a><br />';
												}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<!-- Forms -->
					<div class="row">
						<div class="col-sm-7 col-md-6">
							<div id="collapse-group-4" class="panel-group">
								<div class="panel">
									<div class="panel-heading">
										<!--
										<a
											data-toggle="collapse"
											data-parent="#collapse-group-4"
											href="#collapse-4">
											Forms
										</a>
										-->
										<a href="http://www.famu.edu/index.cfm?hr&Forms" target="_blank">Forms</a>
									</div>
									<div id="collapse-4" class="panel-collapse collapse">
										<div class="panel-body">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<!-- Checklist -->
					<div class="row">
						<div class="col-sm-7 col-md-6">
							<div id="collapse-group-5" class="panel-group">
								<div class="panel">
									<div class="panel-heading">
										<a
											data-toggle="collapse"
											data-parent="#collapse-group-5"
											href="#collapse-5">
											Checklist
										</a>
									</div>
									<div id="collapse-5" class="panel-collapse collapse">
										<div class="panel-body">
											<a href="http://www.famu.edu/index.cfm?hr&Forms#N" target="_blank">New Employee Sign-Up Checklist</a><br />
											<?php
												// Create a link for each file in this category and pay plan
												$links = getFileLinks(1, 'usps', $fileNames_matrix);
												$popover_content = "";

												foreach ($links as $linkName => $linkPath){
													echo '<a href="' . $linkPath . '" target="_blank">' . $linkName . '</a><br />';
												}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- A&P Tab -->
				<div id="ap-tab" class="tab-pane fade">
					<!-- Process Steps -->
					<div class="row">
						<div class="col-sm-7 col-md-6">
							<div id="collapse-group-6" class="panel-group">
								<div class="panel">
									<div class="panel-heading">
										<a
											data-toggle="collapse"
											data-parent="#collapse-group-6"
											href="#collapse-6">
											Process Steps
										</a>
									</div>
									<div id="collapse-6" class="panel-collapse collapse">
										<div class="panel-body">
											<?php
												// Create a link for each file in this category and pay plan
												$links = getFileLinks(0, 'ap', $fileNames_matrix);
												$popover_content = "";

												foreach ($links as $linkName => $linkPath){
													echo '<a href="' . $linkPath . '" target="_blank">' . $linkName . '</a><br />';
												}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<!-- Forms -->
					<div class="row">
						<div class="col-sm-7 col-md-6">
							<div id="collapse-group-7" class="panel-group">
								<div class="panel">
									<div class="panel-heading">
										<!--
										<a
											data-toggle="collapse"
											data-parent="#collapse-group-7"
											href="#collapse-7">
											Forms
										</a>
										-->
										<a href="http://www.famu.edu/index.cfm?hr&Forms" target="_blank">Forms</a>
									</div>
									<div id="collapse-7" class="panel-collapse collapse">
										<div class="panel-body">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<!-- Checklist -->
					<div class="row">
						<div class="col-sm-7 col-md-6">
							<div id="collapse-group-8" class="panel-group">
								<div class="panel">
									<div class="panel-heading">
										<a
											data-toggle="collapse"
											data-parent="#collapse-group-8"
											href="#collapse-8">
											Checklist
										</a>
									</div>
									<div id="collapse-8" class="panel-collapse collapse">
										<div class="panel-body">
											<a href="http://www.famu.edu/index.cfm?hr&Forms#N" target="_blank">New Employee Sign-Up Checklist</a><br />
											<?php
												// Create a link for each file in this category and pay plan
												$links = getFileLinks(1, 'ap', $fileNames_matrix);
												$popover_content = "";

												foreach ($links as $linkName => $linkPath){
													echo '<a href="' . $linkPath . '" target="_blank">' . $linkName . '</a><br />';
												}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Exec Tab -->
				<?php if ($loggedIn) { // INTERNAL ONLY ?>
					<div id="exec-tab" class="tab-pane fade">
						
						<!-- Process Steps -->
						<div class="row">
							<div class="col-sm-7 col-md-6">
								<div id="collapse-group-9" class="panel-group">
									<div class="panel">
										<div class="panel-heading">
											<a
												data-toggle="collapse"
												data-parent="#collapse-group-9"
												href="#collapse-9">
												Process Steps
											</a>
										</div>
										<div id="collapse-9" class="panel-collapse collapse">
											<div class="panel-body">
												<?php
													// Create a link for each file in this category and pay plan
													$links = getFileLinks(0, 'exec', $fileNames_matrix);
													$popover_content = "";

													foreach ($links as $linkName => $linkPath){
														echo '<a href="' . $linkPath . '" target="_blank">' . $linkName . '</a><br />';
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>


						<!-- Forms -->
						<div class="row">
							<div class="col-sm-7 col-md-6">
								<div id="collapse-group-10" class="panel-group">
									<div class="panel">
										<div class="panel-heading">
											<!--
											<a
												data-toggle="collapse"
												data-parent="#collapse-group-10"
												href="#collapse-10">
												Forms
											</a>
											-->
											<a href="http://www.famu.edu/index.cfm?hr&Forms" target="_blank">Forms</a>
										</div>
										<div id="collapse-10" class="panel-collapse collapse">
											<div class="panel-body">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>


						<!-- Checklist -->
						<div class="row">
							<div class="col-sm-7 col-md-6">
								<div id="collapse-group-11" class="panel-group">
									<div class="panel">
										<div class="panel-heading">
											<a
												data-toggle="collapse"
												data-parent="#collapse-group-11"
												href="#collapse-11">
												Checklist
											</a>
										</div>
										<div id="collapse-11" class="panel-collapse collapse">
											<div class="panel-body">
												<a href="http://www.famu.edu/index.cfm?hr&Forms#N" target="_blank">New Employee Sign-Up Checklist</a><br />
												<?php
													// Create a link for each file in this category and pay plan
													$links = getFileLinks(1, 'exec', $fileNames_matrix);
													$popover_content = "";

													foreach ($links as $linkName => $linkPath){
														echo '<a href="' . $linkPath . '" target="_blank">' . $linkName . '</a><br />';
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Fac Tab -->
					<div id="fac-tab" class="tab-pane fade">

						<!-- Process Steps -->
						<div class="row">
							<div class="col-sm-7 col-md-6">
								<div id="collapse-group-12" class="panel-group">
									<div class="panel">
										<div class="panel-heading">
											<a
												data-toggle="collapse"
												data-parent="#collapse-group-12"
												href="#collapse-12">
												Process Steps
											</a>
										</div>
										<div id="collapse-12" class="panel-collapse collapse">
											<div class="panel-body">
												<?php
													// Create a link for each file in this category and pay plan
													$links = getFileLinks(0, 'fac', $fileNames_matrix);
													$popover_content = "";

													foreach ($links as $linkName => $linkPath){
														echo '<a href="' . $linkPath . '" target="_blank">' . $linkName . '</a><br />';
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>


						<!-- Forms -->
						<div class="row">
							<div class="col-sm-7 col-md-6">
								<div id="collapse-group-13" class="panel-group">
									<div class="panel">
										<div class="panel-heading">
											<!--
											<a
												data-toggle="collapse"
												data-parent="#collapse-group-13"
												href="#collapse-13">
												Forms
											</a>
											-->
											<a href="http://www.famu.edu/index.cfm?hr&Forms" target="_blank">Forms</a>
										</div>
										<div id="collapse-13" class="panel-collapse collapse">
											<div class="panel-body">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>


						<!-- Checklist -->
						<div class="row">
							<div class="col-sm-7 col-md-6">
								<div id="collapse-group-14" class="panel-group">
									<div class="panel">
										<div class="panel-heading">
											<a
												data-toggle="collapse"
												data-parent="#collapse-group-14"
												href="#collapse-14">
												Checklist
											</a>
										</div>
										<div id="collapse-14" class="panel-collapse collapse">
											<div class="panel-body">
												<a href="http://www.famu.edu/index.cfm?hr&Forms#N" target="_blank">New Employee Sign-Up Checklist</a><br />
												<?php
													// Create a link for each file in this category and pay plan
													$links = getFileLinks(1, 'fac', $fileNames_matrix);
													$popover_content = "";

													foreach ($links as $linkName => $linkPath){
														echo '<a href="' . $linkPath . '" target="_blank">' . $linkName . '</a><br />';
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } // END INTERNAL ONLY ?> 
			</div>
		</div>
	</div>
</div>
