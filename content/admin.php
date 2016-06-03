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
					<option value="ops">OPS</option>
					<option value="usps">USPS</option>
					<option value="ap">A&amp;P</option>
					<option value="exec">Exec</option>
					<option value="fac">Fac</option>
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
				<div class="col-lg-8 light-text">
					Last updated: 5/24/2016 3:43pm
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
				<div class="col-lg-8 light-text">
					Last updated: 5/24/2016 3:44pm
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-lg-5">
					<label for="upload-formPacket">Forms Packet</label><br />
					<div class="input-group">
						<span class="input-group-btn">
							<span class="btn btn-primary btn-file">
								Browse <input type="file" name="upload-formsPacket" id="upload-formsPacket">
							</span>
						</span>
						<input type="text" class="form-control" readonly="readonly">
						<span class="input-group-btn">
							<span class="btn btn-success">
								<span class="glyphicon glyphicon-upload"></span>
								Upload
							</span>
						</span>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-8 light-text">
					Last updated: 5/24/2016 3:45pm
				</div>
			</div>
		</div>
	</form>
</div>
