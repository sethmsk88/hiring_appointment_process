<div class="container">

	<div class="row">
		<div class="col-lg-12">
			<h3>Update Files</h3>
		</div>
	</div>

	<br />

	<form
		name="uploadDoc-form"
		id="uploadDoc-form"
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

		<div class="form-group">

			<div class="row">
				<div class="col-lg-4">
					<label for="upload-processSteps">Process Steps</label><br />
					<div class="input-group">
						<span class="input-group-btn">
							<span class="btn btn-primary btn-file">
								Browse <input type="file" name="upload-processSteps" id="upload-processSteps">
							</span>
						</span>
						<input type="text" class="form-control" readonly="readonly">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-8">
					Last updated: 5/24/2016 3:42pm
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 form-group">
				<label for="upload-checklist">Checklist</label><br />
				<div class="input-group">
					<span class="input-group-btn">
						<span class="btn btn-primary btn-file">
							Browse <input type="file" name="upload-checklist" id="upload-checklist">
						</span>
					</span>
					<input type="text" class="form-control" readonly="readonly">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 form-group">
				<label for="upload-formPacket">Forms Packet</label><br />
				<div class="input-group">
					<span class="input-group-btn">
						<span class="btn btn-primary btn-file">
							Browse <input type="file" name="upload-formsPacket" id="upload-formsPacket">
						</span>
					</span>
					<input type="text" class="form-control" readonly="readonly">
				</div>
			</div>
		</div>
	</form>
</div>
