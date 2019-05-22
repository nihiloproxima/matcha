<div id="public" class="mt-5">
	<div class="bp3-card bp3-elevation-2 col-md-8 offset-md-2" style="margin-bottom:150px;">
		<h1>Public informations</h1>
		<?php
		if (isset($error)) {
			echo '<p class="error-msg center">' . $error . '</p><br />' . PHP_EOL;
		}
		?>
		<form action="/index.php/Profile/edit_public_informations" method="post" enctype="multipart/form-data" class="mb-3">

			<div class="text-left">
				<p class="h4 mt-3">Orientation</p>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="gender">Your gender :</label>
							<br />
							<select name="gender" class="selectpicker" data-style="bp3-input">
								<option value="Non-binary" <?php if ($_SESSION['gender'] == 'Non-binary') {
																echo "selected ";
															} ?>>Non-binary
								</option>
								<option value="Female" <?php if ($_SESSION['gender'] == 'Female') {
															echo "selected ";
														} ?>>Female
								</option>
								<option value="Male" <?php if ($_SESSION['gender'] == 'Male') {
															echo "selected ";
														} ?>>Male
								</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="target_gender">What do you search ?</label>
							<br />
							<select name="target_gender" class="selectpicker" selected="Female" data-style="bp3-input">
								<option value="" <?php if ($_SESSION['target_gender'] == '') {
														echo "selected ";
													}
													?>>All
								</option>
								<option value="Non-binary" <?php if ($_SESSION['target_gender'] == 'Non-binary') {
																echo "selected ";
															} ?>>Non-binary
								</option>
								<option value="Female" <?php if ($_SESSION['target_gender'] == 'Female') {
															echo "selected ";
														} ?>>Female
								</option>
								<option value="Male" <?php if ($_SESSION['target_gender'] == 'Male') {
															echo "selected ";
														} ?>>Male
								</option>
							</select>
						</div>
					</div>
				</div>

				<p class="h3 mt-3">About you</p>
				<div class="bp3-form-group"><label class="bp3-label" for="age">Enter your age : <span class="bp3-text-muted">(required)</span></label>
					<div class="bp3-form-content">
						<div class="bp3-input-group"><input style="width:25%;" type="number" name="age" placeholder="Ex: 21" min="18" max="120" required class="bp3-input" value="<?php if (isset($_SESSION['age'])) {
																																														echo $_SESSION['age'];
																																													} ?>" style="padding-right: 10px;"></div>
					</div>
				</div>
				<div class="form-group">
					<label for="tags">Add some tags that define you!</label>
					<br />
					<select id="tag-select" name="tags[]" class="form-control tag-select" multiple="multiple" style="width:100%;" data-style="bp3-input">
						<?php foreach ($tags as $tag) : ?>
							<option value="<?= $tag['name'] ?>" <?php
																if (isset($user_tags) && in_array($tag['id'], $user_tags)) {
																	echo 'selected';
																} ?>><?= $tag['name'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group text-left mt-1">
					<label for="bio" class="has-text-left">Describe you in a few words!</label>
					<br />
					<textarea id="quote-content" type="text" class="form-control bp3-input" rows="3" maxlength="150" name="bio" placeholder="Bio" value="<?php if (isset($_SESSION['bio'])) {
																																								echo $_SESSION['bio'];
																																							} ?>"><?php if (isset($_SESSION['bio'])) {
			echo $_SESSION['bio'];
		} ?></textarea>
					<button id="get-another-quote-button" class="bp3-button bp3-intent-primary mt-3">Lazy ? Get random
						quote.</button>
					<br />
				</div>
			</div>
			<div class="text-left">
				<p class="h3 mt-3">Pictures</p>

				<div class="form-group">
					<p>Upload some pics of you (no nudes please...)</p>
					<label class="bp3-file-input .modifier">
						<input type="file" id="files_input" name="files[]" multiple :modifier accept="image/x-png,image/jpeg" onchange="check_files()" />
						<span class="bp3-file-upload-input">Choose file...</span>
					</label>
					<p id="new_images"></p>
					<div id="new_pictures_preview" class="row mt-2">

					</div>
				</div>
				<div class="form-group">
					<label>Your pictures : </label>
					<br />
					<div id="pictures" class="row mt-2">
						<?php foreach ($pictures as $picture) : ?>
							<div id="picture-<?= $picture['id'] ?>" class="pictures_preview hover-box has-text-centered">
								<div class="image is-96x96" style="margin:10px;padding:10px">
									<img src="/<?= $picture['path'] ?>">
								</div>
								<a class="button text-white is-danger" style="width:70%;" onclick="delete_picture('<?= $picture['id'] ?>')">Delete</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="text-left">
				<p class="h3 mt-3">Location</p>

				<div class="form-group">
					<label>Address (only your city will be visible to others)</label>
					<br />
					<input id="user_input_autocomplete_address" class="form-control bp3-input" placeholder="Your address..." value="<?php echo $address['formatted_address']; ?>">
				</div>
			</div>

			<input id="formatted_address" name="formatted_address" type="hidden">
			<input id="street_number" name="street_number" type="hidden">
			<input id="route" name="route" type="hidden">
			<input id="locality" name="locality" type="hidden">
			<input id="country" name="country" type="hidden">
			<input id="postal_code" name="postal_code" type="hidden">
			<input id="lat" name="lat" type="hidden">
			<input id="lng" name="lng" type="hidden">

			<button id="submit-profile" class="bp3-button bp3-intent-primary mt-3" type="submit">Save changes</button>
			<input type="hidden" name="profil_pic">
			<br />
		</form>
	</div>

</div>