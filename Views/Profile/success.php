<?php
if (!isset($_SESSION['username'])) {
    $url = 'Location: ' . WEBROOT;
    header($url);
}
?>
<div class="bp3-card bp3-elevation-2 col-md-8 offset-md-2 mt-5">
        <h1>Edit profile</h1>
        <br/>
        <p class="bp3-intent-success msg center">Profil updated successfully.</p>
        <p class="center"><a href="/index.php/profile/">Return</a></p>
</div>