<div class="tabs is-centered">
	<ul>
		<li id="li-visits" class="is-active">
			<a onclick="show_visits()">
				<span class="icon is-small"><i class="fas fa-user" aria-hidden="true"></i></span>
				<span>Visits on your profile</span>
			</a>
		</li>
		<li id="li-userlikes">
			<a onclick="show_userlikes()">
				<span class="icon is-small"><i class="fas fa-heart" aria-hidden="true"></i></span>
				<span>People you liked</span>
			</a>
		</li>
		<li id="li-likes">
			<a onclick="show_likes()">
				<span class="icon is-small"><i class="fas fa-heart" aria-hidden="true"></i></span>
				<span>People who liked you</span>
			</a>
		</li>
		<li id="li-match">
			<a onclick="show_match()">
				<span class="icon is-small"><i class="fas fa-grin-hearts"></i></span>
				<span>Your matches</span>
			</a>
		</li>
		<li id="li-blacklist">
			<a onclick="show_blacklist()">
				<span class="icon is-small"><i class="fas fa-cross" aria-hidden="true"></i></span>
				<span>Blacklist</span>
			</a>
		</li>
	</ul>
</div>

<div id="visits" class="bp3-card col-md-10 offset-md-1 is-hidden">
	<?php $count = count($visits);
if ($count > 0) {
    $s = $count > 1 ? "s" : "";
    echo "<p>You've received " . $count . " visit" . $s . ".</p><br/>";
}?>
	<?php foreach (array_reverse($visits) as $visit): ?>
	<div class="media">
		<div class="image is-64x64" style="margin: 10px;">
			<img class="rounded-circle"
				src="/<?php echo (!empty($visit['path']) ? $visit['path'] : "assets/uploads/default_user.jpeg"); ?>"
				class="mr-3" alt="...">
		</div>
		<div class="media-body pt-2">
			<h5 class="mt-0 mb-2"><span class="timestamp" data-toggle="tooltip" data-placement="top"
					title="<?=$visit['creation_date']?>"><?=$visit['creation_date']?></span></h5>
			<a href="<?php echo "/index.php/profile/" . $visit['username']; ?>"><?=$visit['username']?> </a> visited
			your profile.
		</div>
	</div>
	<?php endforeach;?>
</div>

<div id="userlikes" class="bp3-card col-md-10 offset-md-1 is-hidden">
	<?php $count = count($user_likes);
if ($count > 0) {
    $s = $count > 1 ? "s" : "";
    echo "<p>" . $count . " person" . $s . " liked.</p><br />";
} else {
    echo "<p>No one liked your profile yet :'(</p>";
}?>
	<?php foreach (array_reverse($user_likes) as $like): ?>
	<div class="media">
		<div class="image is-64x64" style="margin: 10px;">
			<img class="rounded-circle"
				src="/<?php echo (!empty($like['path']) ? $like['path'] : "assets/uploads/default_user.jpeg"); ?>"
				class="mr-3" alt="...">
		</div>
		<div class="media-body pt-2">
			<h5 class="mt-0 timestamp mb-2"><?=$like['creation_date']?></h5>
			<p>You liked <a href="<?php echo "/index.php/profile/" . $like['username']; ?>"><?=$like['username']?> </a>
			</p>

		</div>
	</div>
	<?php endforeach;?>
</div>

<div id="likes" class="bp3-card col-md-10 offset-md-1 is-hidden">
	<?php $count = count($likes);
if ($count > 0) {
    $s = $count > 1 ? "s" : "";
    echo "<p>" . $count . " person" . $s . " liked your profile.</p><br />";
} else {
    echo "<p>No one liked your profile yet :'(</p>";
}?>
	<?php foreach (array_reverse($likes) as $like): ?>
	<div class="media">
		<div class="image is-64x64" style="margin: 10px;">
			<img class="rounded-circle"
				src="/<?php echo (!empty($like['path']) ? $like['path'] : "assets/uploads/default_user.jpeg"); ?>"
				class="mr-3" alt="...">
		</div>
		<div class="media-body pt-2">
			<h5 class="mt-0 timestamp mb-2"><?=$like['creation_date']?></h5>
			<p><a href="<?php echo "/index.php/profile/" . $like['username']; ?>"><?=$like['username']?> </a> liked your
				profile.</p>

		</div>
	</div>
	<?php endforeach;?>
</div>

<div id="match" class="bp3-card col-md-10 offset-md-1 is-hidden">
	<?php $count = count($matches);
if ($count > 0) {
    $s = $count > 1 ? "s" : "";
    echo "<p>" . $count . " person" . $s . " matched.</p><br />";
} else {
    echo "<p>No one liked your profile yet :'(</p>";
}?>
	<?php foreach (array_reverse($matches) as $match): ?>
	<div class="media">
		<div class="image is-64x64" style="margin: 10px;">
			<img class="rounded-circle"
				src="/<?php echo (!empty($match['path']) ? $match['path'] : "assets/uploads/default_user.jpeg"); ?>"
				class="mr-3" alt="...">
		</div>
		<div class="media-body pt-2">
			<br />
			You have a match with <a
				href="<?php echo "/index.php/profile/" . $match['username']; ?>"><?=$match['username']?> </a>.
			<button class="bp3-button bp3-intent-primary float-right"
				onclick="contact(<?=$_SESSION['id']?>, <?=$match['id']?>)">Send a message</button>
		</div>
	</div>
	<?php endforeach;?>
</div>

<div id="blacklist" class="bp3-card col-md-10 offset-md-1 is-hidden">
	<?php $count = count($blacklist);
if ($count > 0) {
    $s = $count > 1 ? "s" : "";
    echo "<p>You've blacklisted <span id='blacklist_count'>" . $count . "</span> persons" . $s . ".</p><br/>";
} else {
    echo "<p>Your blacklist is empty";
}?>
	<?php foreach (array_reverse($blacklist) as $entry): ?>
	<div id="blacklist-<?=$entry['id']?>" class="media">
		<div class="image is-64x64" style="margin: 10px;">
			<img class="rounded-circle"
				src="/<?php echo (!empty($entry['path']) ? $entry['path'] : "assets/uploads/default_user.jpeg"); ?>"
				class="mr-3" alt="...">
		</div>
		<div class="media-body pt-2">
			<h5 class="mt-0 mb-2"><span class="timestamp" data-toggle="tooltip" data-placement="top"
					title="<?=$entry['creation_date']?>"><?=$entry['creation_date']?></span></h5>
			<a href="<?php echo "/index.php/profile/" . $entry['username']; ?>"><?=$entry['username']?> </a> is
			blacklisted.
			<button class="bp3-button bp3-intent-danger float-right" onclick="unblacklist(<?=$entry['id']?>)">Remove
				from blacklist</button>
		</div>
	</div>
	<?php endforeach;?>
</div>