  <h1>Members</h1>
  
  <table class="table-sm table-hover bp3-card">
    <thead>
      <tr>
        <th scope="col" style="color:white">ID</th>
        <th scope="col" style="color:white">Profile Picture</th>
        <th scope="col" style="color:white">Email</th>
        <th scope="col" style="color:white">Username</th>
        <th scope="col" style="color:white">First Name</th>
        <th scope="col" style="color:white">Last Name</th>
        <th scope="col" style="color:white">Popularity score</th>
        <th scope="col" style="color:white">Last connection</th>
        <th scope="col" style="color:white">Creation Date</th>
		<th scope="col" style="color:white">See profile</th>
		<th scope="col" style="color:white">Kill user</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($users) > 0) {
        foreach ($users as $user) : ?>
          <tr style="height:100px;margin: 10px 0 10px 0;">
            <th scope="row" class="has-text-white"><?= $user['id'] ?></th>
            <td style="background-image: url('/<?= $user['path'] ?>');background-position:50% 50%;background-size: cover;"></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['first_name'] ?></td>
            <td><?= $user['last_name'] ?></td>
            <td><?= $user['popularity_score'] ?></td>
            <td><?= $user['last_connection'] ?></td>
            <td><?= $user['creation_date'] ?></td>
			<td><a href="/index.php/profile/<?= $user['username'] ?>">See profile</a>
			<td><button onclick="kill_user(<?= $user['id'] ?>)" target="__blank" class="bp3-button bp3-intent-danger">Kill user</button></td>
          </tr>
        <?php endforeach;
    } ?>
    </tbody>
  </table>