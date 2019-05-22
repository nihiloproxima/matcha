<div class="bp3-card bp3-elevation-two">
	<div class="bp3-input-group .modifier">
		<span class="bp3-icon bp3-icon-search"></span>
		<input id="livesearch_user" class="bp3-input" :modifier type="search" placeholder="Search an user" dir="auto" />
		<div id="display" class="mt-3 row">
		</div>
	</div>
	<br />

	<?php foreach ($banned as $user) : ?>
		<?php if (-(strtotime($user['banned']) - time() + 7200) < 0) : ?>
			<div class="bp3-card col-md-3">
				<p><?= $user['username'] ?> is banned until <?php echo date("d/m/Y H:i:s", (strtotime($user['banned']) + 7200)); ?></p>
				<a class="bp3-button bp3-intent-primary" href="/index.php/profile/<?= $user['username'] ?>">Show profile</a>
				<a class="bp3-button bp3-intent-danger" href="/index.php/admin/unban_user/<?= $user['id']?>">Unban user</a>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>