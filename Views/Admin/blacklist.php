<h1>Blacklists</h1>

<?php foreach ($blacklists as $blacklist) : ?>
    <div id="blacklist-<?= $blacklist['blacklisted_id']?>" class="bp3-card bp3-elevation-two .modifier mb-2 ">
        <h5>Blacklisted by <a target="__blank" href="/index.php/profile/<?= $blacklist['sendername'] ?>"><?= $blacklist['sendername'] ?></a></h5>
        <p><?= $blacklist['targetname'] ?> has been blacklisted.</p>
        <div class="row">
            <div class="col-md-6 has-text-left">
                <a href="/index.php/profile/<?= $blacklist['targetname'] ?>" target="__blank" class="bp3-button">Show profile</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>