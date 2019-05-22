<div class="bp3-card row" style="margin: 0px;">
	<div class="col-md-2 col-sm-12 has-text-centered mb-5 centered-when-small">
		<div class="has-text-centered" style="width:128px;height:128px;margin: 0 auto;position:relative">
			<div id="heart" style="display:none"></div>
			<div id="profile_pic"
				style="width:128px;height:128px;background-image: url('<?php echo "/" . $profile_pic['path']; ?>');background-position:50% 50%;background-size: cover;'"
				class="rounded-circle" alt="profil-picture"></div>
		</div>
		<?php if ($user['id'] == $_SESSION['id']): ?>
		<!-- Button trigger modal -->
		<button type="button" class="bp3-button bp3-intent-primary mt-2" data-toggle="modal"
			data-target="#exampleModal">
			Change
		</button>
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<p class="h5 mt-1" style="color:black" id="exampleModalLabel">Select your profile
							picture
						</p>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<?php foreach ($pictures as $picture): ?>
							<div id="<?=$picture['id']?>" class="image is-128x128 col-md-3"
								style="margin:10px;padding:10px;" onclick="selectPp(this.id)">
								<img src="/<?=$picture['path']?>" class="d-block w-100" alt="...">
							</div>
							<?php endforeach;?>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="bp3-button bp3-intent-danger" data-dismiss="modal">Close
						</button>
						<button type="button" class="bp3-button bp3-intent-primary" data-dismiss="modal"
							onclick="savePp()">Save changes
						</button>
					</div>
				</div>

			</div>
		</div>
		<?php endif;?>
	</div>
	<div class="col-sm-10 col-md-7">
		<p><?php
if (-(strtotime($user['last_connection']) - time() + 7200) < 300) {
    echo "<span class='dot online'></span> Online";
} else {
    echo "<span class='dot offline'></span> Offline. Last connection : <span class='timestamp' data-toggle='tooltip' data-placement='top' title='" . $user['last_connection'] . "'>" . $user['last_connection'] . "</span>";
}?></p>
		<h1 title="<?php echo $user['username']; ?>" class="h1"><i class="fa <?php
if ($user['gender'] == "Male") {
    echo "fa-mars";
} else if ($user['gender'] == "Female") {
    echo "fa-venus";
} else {
    echo "fa-mercury";
}

?>"></i>
			<?php echo $user['first_name'] . " " . $user['last_name'] ?>
			(<?php echo $user['age'] ? $user['age'] : "∞"; ?>), <span><?php echo $address['locality']; ?></span></h1>
		<?php echo $address['formatted_address']; ?>
		<p>Interrested in <?=$user['target_gender'];?></p>
		<?php if (isset($user_liked) && $user_liked == true) {
    echo "<p>" . $user['username'] . " liked you!</p>";
}?>
		<h3 class="mt-3 mb-3 h3">
			<?php echo !empty($user['bio']) ? $user['bio'] : $user['username'] . " has not filled a bio yet."; ?>
		</h3>
		<p>
			<?php if (isset($user_tags)):
    foreach ($user_tags as $tag): ?>
			<span class="badge badge-info"><?=$tag['name']?> </span>
			<?php endforeach;
endif;
?>
		</p>
		<?php
if (isset($_SESSION['id']) && $_SESSION['id'] == $user['id']) {
    echo '<a href="/index.php/profile/edit" class="bp3-button bp3-intent-primary mt-2">Edit profile</a>';
}?>
		<?php
if ($user['id'] != $_SESSION['id'] && count($pictures) > 0 && $has_photo == 1): ?>
		<button id='likeBtn' class='button mt-2 <?php echo $current_user_liked == true ? "is-danger" : "is-info"; ?>'
			onclick="like(<?=$_SESSION['id']?>, <?=$user['id']?>)"><?php echo $current_user_liked == true ? $user_liked ? "It's a match!" : "Liked!" : "Like"; ?></button>
		<button id="contact_btn" class="bp3-button bp3-intent-primary float-right"
			style="display:<?php echo $user_liked && $current_user_liked ? "block" : "none"; ?> "
			onclick="contact(<?=$_SESSION['id']?>, <?=$user['id']?>)">Send a message</button>
		<?php endif;?>
	</div>
	<div class="col-md-3 col-sm-12 has-text-centered">
		<div class="has-text-centered mb-5" style="width: 200px;margin:0 auto;">
			<svg viewBox="0 0 36 36" class="circular-chart <?php if ($user['popularity_score'] < 100) {
    echo "orange";
} elseif ($user['popularity_score'] >= 100 && $user['popularity_score'] < 300) {
    echo "green";
} elseif ($user['popularity_score'] >= 300) {
    echo "blue";
}

?>">
				<path class="circle-bg" d="M18 2.0845
          		a 15.9155 15.9155 0 0 1 0 31.831
          		a 15.9155 15.9155 0 0 1 0 -31.831" />
				<path class="circle" stroke-dasharray="<?php echo $user['popularity_score']; ?>, 1000" d="M18 2.0845
          		a 15.9155 15.9155 0 0 1 0 31.831
          		a 15.9155 15.9155 0 0 1 0 -31.831" />
				<text x="18" y="20.35" class="percentage text-white"><?php echo $user['popularity_score']; ?> pts</text>
			</svg>
		</div>
		<div style="position: absolute; bottom:0">
			<?php if ($_SESSION['id'] != $user['id']): ?>
			<?php if ($is_reported == false): ?>
			<button id="report-btn" class="bp3-button bp3-intent-danger"
				onclick="sendReport(<?=$_SESSION['id']?>, <?=$user['id']?>)">Report as fake account
			</button>
			<?php else: ?>
			<button class="bp3-button bp3-intent-danger bp3-disabled">You've reported this user.</button>
			<?php endif;?>
			<?php if ($is_blacklisted == false): ?>
			<button id="blacklist_user" class="bp3-button bp3-intent-warning"
				onclick="blacklistUser(<?=$user['id']?>)">Blacklist this user.
			</button>
			<?php else: ?>
			<button class="bp3-button bp3-intent-success bp3-disabled">User is blacklisted.</button>
			<?php endif;?>
			<?php endif;?>
		</div>
	</div>
</div>

<div class="tabs is-centered">
	<ul>
		<li id="li-pictures" class="is-active">
			<a class="bp3-intent-primary">
				<span class="icon is-small"><i class="fas fa-image" aria-hidden="true"></i></span>
				<span>Pictures</span>
			</a>
		</li>
	</ul>
</div>

<div id="pictures" class="col-md-10 offset-md-1 bp3-card mb-5">
	<?php if (count($pictures) < 1): ?>
	<p><?=$user['username']?> has no pictures to show yet... :(</p>
	<?php else: ?>
	<div class="col-md-6 offset-md-3">
		<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner">
				<?php foreach ($pictures as $picture): ?>
				<div class="carousel-item">
					<img src="/<?=$picture['path']?>" class="d-block w-100" alt="...">
				</div>
				<?php endforeach;?>
			</div>
			<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</div>
	<?php endif;?>
</div>