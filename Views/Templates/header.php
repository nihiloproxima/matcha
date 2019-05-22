<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Matcha</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
		integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.7/dist/css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="/Assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="/Assets/css/main.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
		integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link href="https://unpkg.com/normalize.css@^7.0.0" rel="stylesheet" />
	<!-- Blueprint stylesheets -->
	<link href="https://unpkg.com/@blueprintjs/icons@^3.4.0/lib/css/blueprint-icons.css" rel="stylesheet" />
	<link href="https://unpkg.com/@blueprintjs/core@^3.10.0/lib/css/blueprint.css" rel="stylesheet" />
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	<?php if (isset($_SESSION['id']) && $_SESSION['theme'] == 1 && $_SERVER['PHP_SELF'] != '/index.php'): ?>
	<link rel="stylesheet" type="text/css" href="/Assets/css/darkfix.css">
	<?php endif;?>

</head>

<body <?php if (isset($_SESSION['id'])) {
    if ($_SESSION['theme'] == 1) {
        echo "class='bp3-dark' style='background-color:#273542'";
    }
} else {
    echo "class='bp3-dark' style='background-color:#273542'";
}?>>

	<?php if (!isset($_SESSION['id'])): ?>
	<div class="bp3-navbar navbar navbar-fixed-top">
		<div class="bp3-navbar-group bp3-align-left">
			<div class="bp3-navbar-heading">
				<a href="/" style="text-decoration: none;">Matcha</a>
			</div>
		</div>
		<div class="bp3-navbar-group bp3-align-right">
			<a href="/index.php/login">
				<button type="button" class="bp3-button bp3-intent-primary">
					<span class="bp3-button-text">Login</span>
				</button>
			</a>
			<div class="bp3-navbar-divider">

			</div>
			<a href="/index.php/register">
				<button type="button" class="bp3-button bp3-intent-success">
					<span class="bp3-button-text">Register</span>
				</button></a>
		</div>
	</div>
	<?php else: ?>
	<span id="userid" class="is-hidden"><?php echo $_SESSION['id']; ?></span>
	<div class="bp3-navbar">
		<div class="bp3-navbar-group bp3-align-left">
			<div class="bp3-navbar-heading">
				<a href="/" style="text-decoration: none;">Matcha</a>
			</div>
		</div>
		<div class="bp3-navbar-group bp3-align-right dropdown">
			<a href="/index.php/profile"><?php echo $_SESSION['username']; ?></a>
			<div class="bp3-navbar-divider"></div>


			<div id="notifications" class="dropdown">
				<button id="notifications-button" type="button" class="bp3-button bp3-minimal" type="button"
					id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
						icon="notifications" class="bp3-icon bp3-icon-notifications"><svg data-icon="notifications"
							width="16" height="16" viewBox="0 0 16 16">
							<desc>notifications</desc>
							<path
								d="M8 16c1.1 0 2-.9 2-2H6c0 1.1.9 2 2 2zm6-5c-.55 0-1-.45-1-1V6c0-2.43-1.73-4.45-4.02-4.9 0-.04.02-.06.02-.1 0-.55-.45-1-1-1S7 .45 7 1c0 .04.02.06.02.1A4.992 4.992 0 0 0 3 6v4c0 .55-.45 1-1 1s-1 .45-1 1 .45 1 1 1h12c.55 0 1-.45 1-1s-.45-1-1-1z"
								fill-rule="evenodd"></path>
						</svg></span>
				</button>
				<div id="notifs-body" class="dropdown-menu dropdown-menu-right"
					style="max-height: 400px;overflow-y:scroll;width:400px;" aria-labelledby="dropdownMenuButton">
				</div>
			</div>

			<a href="/index.php/chat">
				<button id="messages-button" type="button" class="bp3-button bp3-minimal">
					<span icon="chat" class="bp3-icon bp3-icon-chat">
						<svg data-icon="chat" width="16" height="16" viewBox="0 0 16 16">
							<desc>chat</desc>
							<path
								d="M6 10c-1.1 0-2-.9-2-2V3H1c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1v2a1.003 1.003 0 0 0 1.71.71L5.41 13H10c.55 0 1-.45 1-1v-1.17l-.83-.83H6zm9-10H6c-.55 0-1 .45-1 1v7c0 .55.45 1 1 1h4.59l2.71 2.71c.17.18.42.29.7.29.55 0 1-.45 1-1V9c.55 0 1-.45 1-1V1c0-.55-.45-1-1-1z"
								fill-rule="evenodd"></path>
						</svg>
					</span>
				</button>
			</a>

			<div id="settings" class="dropdown">
				<button class="bp3-button bp3-minimal " type="button" data-toggle="dropdown" aria-haspopup="true"
					aria-expanded="false">
					<span icon="cog" class="bp3-icon bp3-icon-cog">
						<svg data-icon="cog" width="16" height="16" viewBox="0 0 16 16">
							<desc>cog</desc>
							<path
								d="M15.19 6.39h-1.85c-.11-.37-.27-.71-.45-1.04l1.36-1.36c.31-.31.31-.82 0-1.13l-1.13-1.13a.803.803 0 0 0-1.13 0l-1.36 1.36c-.33-.17-.67-.33-1.04-.44V.79c0-.44-.36-.8-.8-.8h-1.6c-.44 0-.8.36-.8.8v1.86c-.39.12-.75.28-1.1.47l-1.3-1.3c-.3-.3-.79-.3-1.09 0L1.82 2.91c-.3.3-.3.79 0 1.09l1.3 1.3c-.2.34-.36.7-.48 1.09H.79c-.44 0-.8.36-.8.8v1.6c0 .44.36.8.8.8h1.85c.11.37.27.71.45 1.04l-1.36 1.36c-.31.31-.31.82 0 1.13l1.13 1.13c.31.31.82.31 1.13 0l1.36-1.36c.33.18.67.33 1.04.44v1.86c0 .44.36.8.8.8h1.6c.44 0 .8-.36.8-.8v-1.86c.39-.12.75-.28 1.1-.47l1.3 1.3c.3.3.79.3 1.09 0l1.09-1.09c.3-.3.3-.79 0-1.09l-1.3-1.3c.19-.35.36-.71.48-1.1h1.85c.44 0 .8-.36.8-.8v-1.6a.816.816 0 0 0-.81-.79zm-7.2 4.6c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"
								fill-rule="evenodd"></path>
						</svg>
					</span>
				</button>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
					<div class="bp3-popover-content">
						<ul>
							<button id="theme_switch" class="bp3-menu-item bp3-icon-moon fill"
								onclick="switchTheme()">Light
								theme</button>
							<a class="bp3-menu-item bp3-icon-dashboard fill"
								href="/index.php/profile/activity">Activity</a>
							<a class="bp3-menu-item bp3-icon-user fill" href="/index.php/profile/edit">Public
								profile</a>
							<?php if ($_SESSION['role'] == "admin"): ?>
							<a class="bp3-menu-item bp3-icon-settings" href="/index.php/admin">Admin pannel</a>
							<?php endif;?>
							<a class="bp3-menu-item bp3-icon-cog fill"
								href="/index.php/profile/settings">Settings...</a>
							<li class="bp3-menu-divider"></li>
							<li class="">
								<a class="bp3-menu-item bp3-popover-dismiss bp3-minimal fill"
									href="/index.php/user/disconnect"><span icon="log-out"
										class="bp3-icon bp3-icon-log-out"><svg data-icon="log-out" width="16"
											height="16" viewBox="0 0 16 16">
											<desc>log-out</desc>
											<path
												d="M7 14H2V2h5c.55 0 1-.45 1-1s-.45-1-1-1H1C.45 0 0 .45 0 1v14c0 .55.45 1 1 1h6c.55 0 1-.45 1-1s-.45-1-1-1zm8.71-6.71l-3-3a1.003 1.003 0 0 0-1.42 1.42L12.59 7H6c-.55 0-1 .45-1 1s.45 1 1 1h6.59l-1.29 1.29c-.19.18-.3.43-.3.71a1.003 1.003 0 0 0 1.71.71l3-3c.18-.18.29-.43.29-.71 0-.28-.11-.53-.29-.71z"
												fill-rule="evenodd"></path>
										</svg></span>
									<div class="bp3-text-overflow-ellipsis bp3-fill">Disconnect</div>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif;?>