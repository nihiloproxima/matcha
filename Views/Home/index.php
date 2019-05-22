<?php if (!isset($_SESSION['id'])): ?>
<section class="hero is-link is-fullheight-with-navbar">
	<div class="hero-body">
		<div class="container">
			<p class="h1">
				Matcha
			</p>
			<p class="subtitle">
				Bringing people together at its best.
			</p>
			<a class="button is-light" href="/index.php/register">Get started.</a>
		</div>
	</div>
</section>
<?php else: ?>
<section class="hero is-small bp3-card">
	<div class="hero-body">
		<div class="container">
			<h1>Advanced search</h1>
			<a class="bp3-button bp3-intent-primary" href="/index.php/search">Go to search section</a>
		</div>
	</div>
</section>

<!-- Popular users -->

<div id="popular" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner col-md-10 offset-md-1">
		<h1>Popular users</h1>
		<?php
$i = -1;
foreach ($users as $user):
    if (++$i == 0 || $i == 3 || $i == 6 || $i == 9 || $i == 12 || $i == 15 || $i == 18): ?>
		<div class="carousel-item <?php if ($i == 0) {
        echo "active";
    }?>">
			<div class="card-deck">
				<?php endif;?>
				<div class="col-md-4" style="margin: 15px 0px;">
					<div class="col-md-12 bp3-card"><img class="card-img-top" src="/<?=$user['path']?>">
						<div class="card-body">
							<h5 class="card-title"><a
									href="/index.php/profile/<?=$user['username']?>"><?=$user['username']?></a>
								(<?=$user['age']?>)</h5>
							<p class="card-text" title="<?=$user['bio']?>"><?php echo (substr($user['bio'], 0, 40));
if (strlen($user['bio']) > 40) {
    echo "...";
} ?></p>
							<p><?php echo count($user['shared_tags']) ?> shared tags</p>
							<?php foreach ($user['shared_tags'] as $tag): ?>
							<span class="badge badge-info"><?=$tag['name']?></span>
							<?php endforeach;?>
							<p class="card-text" style="position:absolute;bottom:10px;"><small
									class="text-muted">Popularity :
									<?=$user['popularity_score']?> pts</small></p>
							<p><?=$user['distance']?></p>
						</div>
					</div>
				</div>
				<?php if ($i == 2 || $i == 5 || $i == 8 || $i == 11 || $i == 14 || $i == 17 || $i == 20): ?>
			</div>
		</div>
		<?php endif;?>
		<?php endforeach;?>
		<a class="carousel-control-prev" href="#popular" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#popular" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
</div>

<!-- Recommandations -->
<?php if ($_SESSION['profile_complete'] == 1): ?>
<div id="suggested" class="col-md-10 offset-md-1 mt-2 row">
	<h1 class="float-left col-md-12">Recommended users for you :</h1>
	<div class="col-md-12">
		<button type="button" class="bp3-button bp3-intent-primary mt-2" data-toggle="modal"
			data-target="#exampleModal">
			Options
		</button>
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<p class="h5 mt-1" style="color:black" id="exampleModalLabel">
							Filters
						</p>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="bp3-form-group col-md-6">
								<label class="has-text-black" for="age_min">Minimum age :</label>
								<div class="bp3-form-content">
									<div class="bp3-input-group">
										<input type="number" name="age_min" class="form-control" min="18" max="120"
											style="padding-right: 10px;">
									</div>
								</div>
							</div>
							<div class="bp3-form-group col-md-6">
								<label class="has-text-black" for="age_max">Maximum age :</label>
								<div class="bp3-form-content">
									<div class="bp3-input-group">
										<input type="number" name="age_max" class="form-control" min="18" max="120"
											style="padding-right: 10px;">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="bp3-form-group col-md-6">
								<label class="has-text-black" for="popularity_min">Minimum popularity score
									:</label>
								<div class="bp3-form-content">
									<div class="bp3-input-group">
										<input type="number" name="popularity_min" class="form-control" min="0"
											style="padding-right: 10px;">
									</div>
								</div>
							</div>
							<div class="bp3-form-group col-md-6">
								<label class="has-text-black" for="popularity_max">Maximum popularity score
									:</label>
								<div class="bp3-form-content">
									<div class="bp3-input-group">
										<input type="number" name="popularity_max" class="form-control" min="0"
											style="padding-right: 10px;">
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="bp3-form-group col-md-6">
								<label class="has-text-black" for="city">City :</label>
								<div class="bp3-form-content">
									<div class="bp3-input-group">
										<input type="text" name="city" placeholder="Lyon..." class="form-control"
											value="" style="padding-right: 10px;">
									</div>
								</div>
							</div>
							<div class="bp3-form-group col-md-6">
								<label class="has-text-black" for="password">Distance from me :</label>
								<div class="bp3-form-content">
									<div class="bp3-input-group">
										<input type="number" name="distance" placeholder="5" class="form-control"
											value="" style="padding-right: 10px;">
									</div>
								</div>
							</div>
						</div>
						<div class="bp3-form-group">
							<label class="has-text-black" for="password">Add some tags :</label>
							<div class="bp3-form-content">
								<select id="tag-select" name="tags[]" class="tag-select" multiple="multiple"
									style="width:100%;color:white" data-style="bp3-input">
									<?php foreach ($tags as $tag): ?>
									<option class="has-text-black" style="color:black !important"
										value="<?=$tag['name']?>"><?=$tag['name']?>
									</option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<select id="options" name="sort" class="custom-select" :modifier">
							<option selected>Sort results...</option>
							<option value="age_asc">Young to old</option>
							<option value="age_desc">Old to young</option>
							<option value="popularity_desc">Popularity -</option>
							<option value="popularity_asc">Popolarity +</option>
							<option value="shared_tags_desc">Shared tags +</option>
							<option value="shared_tags_asc">Shared tags -</option>
							<option value="location">Location</option>
							<option value="score">Score</option>
							<option value="distance_asc">Distance -</option>
							<option value="distance_desc">Distance +</option>
						</select>
					</div>
					<div class="modal-footer">
						<button type="button" class="bp3-button bp3-intent-danger" data-dismiss="modal">Close
						</button>
						<button type="button" class="bp3-button bp3-intent-primary" data-dismiss="modal"
							onclick="perform_suggested()">Apply filters
						</button>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div id="suggest_results" class="row">
		<?php foreach ($suggested as $user): ?>
		<div class="col-md-4" style="margin:15px 0 15px 0">
			<div class="col-md-12 bp3-card">
				<img class="card-img-top" src="/<?=$user['path']?>">
				<div class="card-body">
					<h5 class="card-title">
						<a href="/index.php/profile/<?=$user['username']?>"><?=$user['username']?></a>
						(<?=$user['age']?>)</h5>
					<p class="card-text"><?=$user['bio']?></p>
					<p><?=count($user['shared_tags'])?> shared tags</p>
					<?php foreach ($user['shared_tags'] as $tag): ?>
					<span class='badge badge-info'><?=$tag['name']?></span>
					<?php endforeach;?>
					<p class="card-text"><small class="text-muted">Popularity : <?=$user['popularity_score']?>
							pts</small></p>
					<p>Matching : <?=$user['score']?>%</p>
					<p><?=$user['locality']?> (<?=$user['distance']?>)</p>
				</div>
			</div>
		</div>
		<?php endforeach;?>
	</div>
</div>
<?php else : ?>
<div id="suggested" class="col-md-10 offset-md-1 mt-2 mb-5 row">
	<h1 class="float-left col-md-12 mb-3">Recommended users for you :</h1>
	<p class="mb-5">You should complete your profile to access your suggestions!</p>
</div>
<?php endif;?>

<?php endif;?>