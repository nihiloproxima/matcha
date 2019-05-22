<br />
<h1 class="title is-3 center">Notifications</h1>
<br />
<div class="tile is-ancestor">
	<div class="tile is-4"></div>
	<div class="tile is-4 is-vertical">
	<?php if (!$notifications) : ?>
		<p>You have no new notification</p>
	<?php else : 
	foreach ($notifications as $notif) : ?>
	    
			<div class="notification <?php echo $notif['object'] == "New comment" ? "is-primary" : "is-danger"; ?>">
				<a href="/index.php/notification/delete?id=<?= $notif['id'] ?>&user_id=<?= $notif['user_id'] ?>" class="delete">
				</a>
				<a href="/index.php/post?post_id=<?= $notif['post_id'] ?>" style="text-decoration:none;">
				<p><?= $notif['object'] ?>!</p>
				<p><?= $notif['content'] ?>
				<p class="timestamp"><?= $notif['creation_date'] ?></p>
				</a>
	    	</div>
	<?php endforeach; endif; ?>
	</div>
	<div class="tile is-4"></div>
</div>

<script>

