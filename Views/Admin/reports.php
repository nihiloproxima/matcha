<h1>Reports</h1>

<?php foreach ($reports as $report) : ?>
	<div id="report-<?= $report['reported_id'] ?>" class="bp3-card bp3-elevation-two .modifier mb-2 ">
		<h5>User report sent by <a target="__blank" href="/index.php/profile/<?= $report['sendername'] ?>"><?= $report['sendername'] ?></a></h5>
		<p><?= $report['username'] ?> has been reported as a fake account.</p>
		<div class="row">
			<div class="col-md-6 has-text-left">
				<a href="/index.php/profile/<?= $report['username'] ?>" target="__blank" class="bp3-button">Show profile</a>
			</div>
			<div class="col-md-6 has-text-right">
				<button onclick="kill_user(<?= $report['userid'] ?>)" target="__blank" class="bp3-button bp3-intent-danger">Kill user</button>
			</div>
		</div>
	</div>
<?php endforeach; ?>
<?php if (count($reports) == 0) {
	echo '<p>No reports at the moment</p>';
} ?>