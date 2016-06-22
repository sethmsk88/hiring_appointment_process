<div
	class="modal fade"
	id="editModal"
	role="dialog"
	aria-labelledby=""
	aria-hidden="true">

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button
					type="button"
					class="close"
					data-dismiss="modal"
					aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Edit File</h4>
			</div>
			<div class="modal-body">
				<form
					name="editFile-form"
					id="editFile-form"
					role="form"
					method="post"
					action="./content/act_editUpload.php">
					
					<!-- ID of file that is being edited -->
					<input type="hidden" name="edit-fileID" id="edit-fileID" value="" />

					<div class="row">
						<div class="col-lg-12 form-group">
							<label for="edit-linkName">Link Name</label>
							<input
								type="text"
								name="edit-linkName"
								id="edit-linkName"
								class="form-control"
								value="">
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12 form-group">
							<label for="edit-payPlan">Pay Plan</label>
							<select name="edit-payPlan" id="edit-payPlan" class="form-control">
								<option value="-1"></option>
								<option value="ap">A&amp;P</option>
								<option value="exec">Exec</option>
								<option value="fac">Fac</option>
								<option value="ops">OPS</option>
								<option value="usps">USPS</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12 form-group">
							<label for="edit-category">Category</label>
							<select name="edit-category" id="edit-category" class="form-control">
								<option value="-1"></option>
								<option value="0">Process Steps</option>
								<option value="1">Checklist</option>
								<option value="2">Forms Packet</option>
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button
					type="button"
					class="btn btn-default"
					data-dismiss="modal">
					Cancel
				</button>
				<button
					type="button"
					class="btn btn-primary"
					id="editSubmit">
					Submit Changes
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// Dialog show event handler
	$('#editModal').on('show.bs.modal', function (e) {

		$clickedButton = $(e.relatedTarget);

		// Get ID of button that was clicked to show modal
		$buttonID = $clickedButton.attr('id');

		// Populate form fields with values from row of clicked button in table
		$linkName = $clickedButton.parent().siblings('.linkName-cell').text();

		$payPlan = $clickedButton.parent().siblings('.payPlan-cell').text();

		$category = $clickedButton.parent().siblings('.category-cell').text();

		// Convert Pay Plan to integer value
		switch ($payPlan) {
			case "A&P":
				$payPlan = "ap";
				break;
			case "Exec":
				$payPlan = "exec";
				break;
			case "Fac":
				$payPlan = "fac";
				break;
			case "OPS":
				$payPlan = "ops";
				break;
			case "USPS":
				$payPlan = "usps";
				break;
			default:
				$payPlan = -1;
		}

		// Convert category to integer value
		switch ($category) {
			case "Process Steps":
				$category = 0;
				break;
			case "Checklist":
				$category = 1;
				break;
			case "Forms Packet":
				$category = 2;
				break;
			default:
				$category = -1;
				break;
		}

		$('#edit-linkName').val($linkName);
		$('#edit-payPlan').val($payPlan);
		$('#edit-category').val($category);

		//$('.modal-body').html($buttonID.match(/\d+$/)[0]);

		/*
			NOTE: Can pass in modal attributes using attributes in the 
			button tag if that method is preferred. (See lines below)
		*/
		// $message = $(e.relatedTarget).attr('data-message');
		// $(this).find('.modal-body p').html($message);
		// $title = $(e.relatedTarget).attr('data-title');
		// $(this).find('.modal-title').text($title);
	});

	// Form submit handler
	$('#editModal').find('.modal-footer #editSubmit').on('click', function() {

		// Get fileID from buttonID
		$fileID = $buttonID.match(/\d+$/)[0];

		// Set hidden input value in modal form
		$('#edit-fileID').val($fileID);

		// Submit the form
		$('#editFile-form').submit();
	});

</script>
