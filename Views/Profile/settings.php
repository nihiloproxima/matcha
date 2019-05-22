<div class="col-md-8 offset-md-2 mt-5 bp3-card">
    <h1 class="mt-3">Account informations</h1>
    <?php
    if (isset($error)) {
        echo '<p class="error-msg center">' . $error . '</p><br />' . PHP_EOL;
    }
    ?>
    <form action="<?php echo WEBROOT . 'index.php/Profile/edit_account_settings'; ?>" method="post" class="mb-3 has-text-left">
        <div class="bp3-form-group"><label class="bp3-label" for="email">Enter your email : <span class="bp3-text-muted">(required)</span></label>
            <div class="bp3-form-content">
                <div class="bp3-input-group"><input type="email" name="email" placeholder="petitponey@wonderland.com" required class="bp3-input" value="<?php
                                                                                                                                                        echo $_SESSION['email']; ?>" style="padding-right: 10px;"></div>
            </div>
        </div>
        <div class="bp3-form-group"><label class="bp3-label" for="username">Enter your username : <span class="bp3-text-muted">(required)</span></label>
            <div class="bp3-form-content">
                <div class="bp3-input-group"><input type="text" name="username" placeholder="Username" required class="bp3-input" value="<?php
                                                                                                                                            echo $_SESSION['username'];
                                                                                                                                            ?>" style="padding-right: 10px;"></div>
            </div>
        </div>
        <div class="bp3-form-group"><label class="bp3-label" for="first_name">Enter your first name : <span class="bp3-text-muted">(required)</span></label>
            <div class="bp3-form-content">
                <div class="bp3-input-group"><input type="text" name="first_name" placeholder="Ex: Donald" required class="bp3-input" value="<?php
                                                                                                                                                echo $_SESSION['first_name'];
                                                                                                                                                ?>" style="padding-right: 10px;"></div>
            </div>
        </div>
        <div class="bp3-form-group"><label class="bp3-label" for="last_name">Enter your last name : <span class="bp3-text-muted">(required)</span></label>
            <div class="bp3-form-content">
                <div class="bp3-input-group"><input type="text" name="last_name" placeholder="Ex: Trump" required class="bp3-input" value="<?php
                                                                                                                                            echo $_SESSION['last_name'];
                                                                                                                                            ?>" style="padding-right: 10px;"></div>
            </div>
        </div>
        <div class="bp3-form-group"><label class="bp3-label" for="password">Enter your old password :</label>
            <div class="bp3-form-content">
                <div class="bp3-input-group"><input type="password" name="old_password" placeholder="Re-enter your password" class="bp3-input" value="" style="padding-right: 10px;"></div>
            </div>
        </div>
        <div class="bp3-form-group"><label class="bp3-label" for="password">New password :</label>
            <div class="bp3-form-content">
                <div class="bp3-input-group"><input type="password" name="new_password" placeholder="MyNewSecurePasswordDeHacker" class="bp3-input" value="" style="padding-right: 10px;"></div>
            </div>
        </div>
        <div class="bp3-form-group"><label class="bp3-label" for="password">Confirm your new password :</label>
            <div class="bp3-form-content">
                <div class="bp3-input-group"><input type="password" name="password_confirm" placeholder="Re-enter your password" class="bp3-input" value="" style="padding-right: 10px;"></div>
            </div>
        </div>
        <label class="bp3-control bp3-checkbox .modifier">
            <input type="checkbox" :modifier name="notifications" <?php echo $_SESSION['notification_mails'] == 0 ? "" : "checked"; ?> />
            <span class="bp3-control-indicator"></span>
            Receive notifications emails
        </label>
        <p class="has-text-left">Forgot your password, uh?<br /> <a href="/index.php/user/forgot_password">Reset
                password</a></p>

        <button type="submit" class="bp3-button bp3-intent-primary col-md-2 offset-md-10" value="Submit">Submit</button>
        <br />
    </form>

</div>