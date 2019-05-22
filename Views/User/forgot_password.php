<div class="bp3-card bp3-elevation-2 col-md-8 offset-md-2 mt-5 mb-5">

	<h1>Send a new password.</h1>
	<br />
	<?php
if (isset($error)) {
    echo '<p class="error-msg center">' . $error . '</p><br />';
}
?>
	<p class="center">
		We will send you a new password on your email adress. <br />
		Please change it back in your account's settings once connected.
	</p>
	<br>
	<form action="<?php echo WEBROOT . 'index.php/user/forgot_password'; ?>" method="post" class="has-text-left">
		<div class="bp3-form-group"><label class="bp3-label" for="email">Enter your email : <span
					class="bp3-text-muted">(required)</span></label>
			<div class="bp3-form-content">
				<div class="bp3-input-group">
					<input type="email" class="bp3-input" name="email" placeholder="Account email" value="<?php if (isset($_SESSION['email'])) {
    echo $_SESSION['email'];
}?>" required>
				</div>
			</div>
		</div>

		<div class="has-text-right">
			<button type="submit" class="bp3-button bp3-intent-primary " value="Submit"><span
					class="bp3-button-text">Send reset email</span></button>
		</div>
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
		<br />
	</form>
	<a href="/index.php/<?php echo (empty($_SESSION['username']) ? 'login' : 'profile/settings'); ?>"
		class="bp3-button">Cancel</a>

</div>