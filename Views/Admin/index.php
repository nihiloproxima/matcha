<div class="col-md-10 offset-md-1 mt-5 row">

	<ul class="bp3-menu bp3-elevation-1 col-md-2" style="max-height:200px;">
		<li>
			<a id="stats_button" class="bp3-menu-item bp3-popover-dismiss bp3-icon-graph fill bp3-active" onclick="show_stats()">
				<div class="bp3-text-overflow-ellipsis bp3-fill">Stats</div>
			</a>
		</li>
		<li class="bp3-menu-divider">
		</li>
		<li><a id="members_button" class="bp3-menu-item bp3-popover-dismiss bp3-icon-people fill" onclick="show_members()">
				<div class="bp3-text-overflow-ellipsis bp3-fill">Members</div>
			</a>
		</li>
		<li>
			<a id="reports_button" class="bp3-menu-item bp3-popover-dismiss bp3-icon-warning-sign fill" onclick="show_reports()">
				<div class="bp3-text-overflow-ellipsis bp3-fill">Reports</div>
			</a>
		</li>
		<li>
			<a id="blacklist_button" class="bp3-menu-item bp3-popover-dismiss bp3-icon-ban-circle fill" onclick="show_blacklist()">
				<div class="bp3-text-overflow-ellipsis bp3-fill">Blacklist</div>
			</a>
		</li>
		<li>
			<a id="banned_button" class="bp3-menu-item bp3-popover-dismiss bp3-icon-blocked-person fill" onclick="show_banned()">
				<div class="bp3-text-overflow-ellipsis bp3-fill">Banned users</div>
			</a>
		</li>
		<li>
			<a id="hydratation_button" class="bp3-menu-item bp3-popover-dismiss bp3-icon-tint fill" onclick="show_hydratation()">
				<div class="bp3-text-overflow-ellipsis bp3-fill">Hydratation</div>
			</a>
		</li>
	</ul>

	<div id="stats" class="col-md-10 current">
		<div class="bp3-card">

			<h1>Some meaningful statistics :</h1>

			<div class="notification col-md-6 is-primary">
				<h2 class="h2">Total users : <h3 class="h3"><?= $stats['total_users'] ?></h3>
				</h2>
			</div>
			<div class="notification col-md-6 is-info">
				<h2 class="h2">Total visits : <h3 class="h3"><?= $stats['total_visits'] ?></h3>
				</h2>
			</div>
			<div class="notification col-md-6  is-danger">
				<h2 class="h2">Total likes : <h3 class="h3"><?= $stats['total_likes'] ?></h3>
				</h2>
			</div>
			<div class="notification col-md-6 is-danger">
				<h2 class="h2">All messages: <h3 class="h3"><?= $stats['total_messages'] ?></h3>
				</h2>
			</div>
		</div>
	</div>

	<div id="members" class="col-md-10 is-hidden">
		<?php $this->loadView('admin/members'); ?>
	</div>

	<div id="reports" class="col-md-10 is-hidden">
		<?php $this->loadView('admin/reports'); ?>
	</div>

	<div id="blacklist" class="col-md-10 is-hidden">
		<?php $this->loadView('admin/blacklist'); ?>
	</div>

	<div id="banned" class="col-md-10 is-hidden">
		<?php $this->loadView('admin/banned'); ?>
	</div>

	<div id="hydratation" class="col-md-10 is-hidden">
		<?php $this->loadView('admin/hydratation'); ?>
	</div>

</div>
<script>
	function show_stats() {
		var active = document.getElementsByClassName("bp3-active");
		for (i = 0; i < active.length; i++) {
			active[i].classList.remove('bp3-active');
		}
		document.getElementsByClassName("current")[0].classList.add("is-hidden");
		document.getElementsByClassName("current")[0].classList.remove("current");
		document.getElementById("stats_button").classList.add('bp3-active');
		document.getElementById('stats').classList.remove("is-hidden");
		document.getElementById('stats').classList.add("current");
	}

	function show_members() {
		var active = document.getElementsByClassName("bp3-active");
		for (i = 0; i < active.length; i++) {
			active[i].classList.remove('bp3-active');
		}
		document.getElementsByClassName("current")[0].classList.add("is-hidden");
		document.getElementsByClassName("current")[0].classList.remove("current");
		document.getElementById("members_button").classList.add('bp3-active');
		document.getElementById('members').classList.remove("is-hidden");
		document.getElementById('members').classList.add("current");
	}

	function show_reports() {
		var active = document.getElementsByClassName("bp3-active");
		for (i = 0; i < active.length; i++) {
			active[i].classList.remove('bp3-active');
		}
		document.getElementsByClassName("current")[0].classList.add("is-hidden");
		document.getElementsByClassName("current")[0].classList.remove("current");
		document.getElementById("reports_button").classList.add('bp3-active');
		document.getElementById('reports').classList.remove("is-hidden");
		document.getElementById('reports').classList.add("current");
	}

	function show_banned() {
		var active = document.getElementsByClassName("bp3-active");
		for (i = 0; i < active.length; i++) {
			active[i].classList.remove('bp3-active');
		}
		document.getElementsByClassName("current")[0].classList.add("is-hidden");
		document.getElementsByClassName("current")[0].classList.remove("current");
		document.getElementById("banned_button").classList.add('bp3-active');
		document.getElementById('banned').classList.remove("is-hidden");
		document.getElementById('banned').classList.add("current");
	}

	function show_blacklist() {
		var active = document.getElementsByClassName("bp3-active");
		for (i = 0; i < active.length; i++) {
			active[i].classList.remove('bp3-active');
		}
		document.getElementsByClassName("current")[0].classList.add("is-hidden");
		document.getElementsByClassName("current")[0].classList.remove("current");
		document.getElementById("blacklist_button").classList.add('bp3-active');
		document.getElementById('blacklist').classList.remove("is-hidden");
		document.getElementById('blacklist').classList.add("current");
	}

	function show_hydratation() {
		var active = document.getElementsByClassName("bp3-active");
		for (i = 0; i < active.length; i++) {
			active[i].classList.remove('bp3-active');
		}
		document.getElementsByClassName("current")[0].classList.add("is-hidden");
		document.getElementsByClassName("current")[0].classList.remove("current");
		document.getElementById("hydratation_button").classList.add('bp3-active');
		document.getElementById('hydratation').classList.remove("is-hidden");
		document.getElementById('hydratation').classList.add("current");
	}
</script>