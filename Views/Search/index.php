<div class="bp3-card bp3-elevation-2">
	<h1>Custom search :</h1>
	<?php
if (isset($error)) {
    echo '<p class="error-msg center">' . $error . '</p><br />' . PHP_EOL;
}
?>
	<div class="mb-3 has-text-left">
		<div class="form-group">
			<label for="target_gender">What do you search ?</label>
			<br />
			<select name="gender" class="selectpicker" data-style="bp3-input">
				<option value="Non-binary">Non-binary</option>
				<option value="Female">Female</option>
				<option value="Male">Male</option>
			</select>
		</div>
		<div class="row">
			<div class="bp3-form-group col-md-6">
				<label class="bp3-label" for="age_min">Minimum age :</label>
				<div class="bp3-form-content">
					<div class="bp3-input-group">
						<input type="number" name="age_min" class="bp3-input" min="18" max="120"
							style="padding-right: 10px;">
					</div>
				</div>
			</div>
			<div class="bp3-form-group col-md-6">
				<label class="bp3-label" for="age_max">Maximum age :</label>
				<div class="bp3-form-content">
					<div class="bp3-input-group">
						<input type="number" name="age_max" class="bp3-input" min="18" max="120"
							style="padding-right: 10px;">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="bp3-form-group col-md-6">
				<label class="bp3-label" for="popularity_min">Minimum popularity score :</label>
				<div class="bp3-form-content">
					<div class="bp3-input-group">
						<input type="number" name="popularity_min" class="bp3-input" min="0"
							style="padding-right: 10px;">
					</div>
				</div>
			</div>
			<div class="bp3-form-group col-md-6">
				<label class="bp3-label" for="popularity_max">Maximum popularity score :</label>
				<div class="bp3-form-content">
					<div class="bp3-input-group">
						<input type="number" name="popularity_max" class="bp3-input" min="0"
							style="padding-right: 10px;">
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="bp3-form-group col-md-6">
				<label class="bp3-label" for="city">City :</label>
				<div class="bp3-form-content">
					<div class="bp3-input-group">
						<input type="text" name="city" placeholder="Lyon..." class="bp3-input" value=""
							style="padding-right: 10px;">
					</div>
				</div>
			</div>
			<div class="bp3-form-group col-md-6">
				<label class="bp3-label" for="password">Distance from me : <span class="bp3-text-muted">(in
						kilometers)</span></label>
				<div class="bp3-form-content">
					<div class="bp3-input-group">
						<input type="number" name="distance" placeholder="5" class="bp3-input" value=""
							style="padding-right: 10px;">
					</div>
				</div>
			</div>
		</div>
		<div class="bp3-form-group">
			<label class="bp3-label" for="password">Add some tags : <span class="bp3-text-muted">(we'll search for
					people matching at least one of them)</span></label>
			<div id="dark" class="bp3-form-content">
				<select id="tag-select" name="tags[]" class="tag-select" multiple="multiple"
					style="width:100%;color:white" data-style="bp3-input">
					<?php foreach ($tags as $tag): ?>
					<option value="<?=$tag['name']?>"><?=$tag['name']?></option>
					<?php endforeach;?>
				</select>
			</div>
		</div>

		<button onclick="perform()" class="bp3-button bp3-intent-primary col-md-2 offset-md-10"><span
				class="bp3-button-text">Search</span></button>

	</div>
</div>

<div id="sort_menu" class="col-md-3 offset-md-8 mt-5 .modifier ">
	<div class="bp3-select bp3-fill float-right">
		<select id="options" :modifier onchange="perform(this.value)">
			<option selected>Sort results...</option>
			<option value="age_asc">Young to old</option>
			<option value="age_desc">Old to young</option>
			<option value="popularity_desc">Popularity -</option>
			<option value="popularity_asc">Popolarity +</option>
			<option value="shared_tags_desc">Shared tags +</option>
			<option value="shared_tags_asc">Shared tags -</option>
			<option value="location">Location</option>
			<option value="distance_asc">Near</option>
			<option value="distance_desc">Far</option>
		</select>
	</div>
</div>
<div id="results" class="col-md-10 offset-md-1 mt-2 row">

</div>

<div class="col-md-12 has-text-centered">
	<div id="waiting" class="spinner-border offset-md-6" role="status" style="display:none;">
		<span class="sr-only">Loading...</span>
	</div>
</div>