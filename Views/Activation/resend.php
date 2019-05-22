<div class="bp3-card bp3-elevation-2 col-md-8 offset-md-2 mt-5">

		<h1>Send a new confirmation email</h1>
		<?php if (isset($error)) : ?>
			<p class="error-msg center"><?php echo $error; ?></p>
		<?php endif; if (!isset($email_sended)) :?>
		<form method="post" action="/index.php/activation/resend" class="has-text-left">
		<div class="bp3-form-group"><label class="bp3-label" for="email">Enter your email : <span class="bp3-text-muted">(required)</span></label>
            <div class="bp3-form-content">
                <div class="bp3-input-group">
                    <input type="email" class="bp3-input" name="email" placeholder="Account email" required>
                </div>
            </div>
        </div>
			<button type="submit" class="bp3-button bp3-intent-primary">Send confirmation email</button>
		</form>
		<br />
		<?php else : ?>
		<p class="center"><?= $email_sended ?></p>
		<?php endif; ?>

</div>