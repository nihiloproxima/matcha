<?php

class UserModel extends Model
{
	public function get_location()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = "185.15.27.37";
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.ipstack.com/" . $ip . "?access_key=1658f359a1cb48d6db7785ef8460e103",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$data = (json_decode($response, true)); //because of true, it's in an array

		$this->update_user_key($_SESSION['id'], 'lat', $data['latitude']);
		$this->update_user_key($_SESSION['id'], 'lng', $data['longitude']);

		return ($this->get_address($data['latitude'], $data['longitude']));
	}

	public function get_address($latitude, $longitude)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude . "&key=AIzaSyB4Ply4txa36YmO0XJdu3OzWVaWgsh-bEw",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		return (json_decode($response, true)); //because of true, it's in an array
	}

	public function get_user($key = null, $value = null)
	{
		if ($key && $value) {
			$stmt = $this->db->prepare("SELECT * FROM `Users` WHERE $key = ?");
			$stmt->execute([$value]);
			return ($stmt->fetch());
		}
		$stmt = $this->db->prepare("SELECT * FROM Users");
		$stmt->execute();
		return ($stmt->fetchAll());
	}


	public function get_live_user($key = null, $value = null)
	{
		if ($key && $value) {
			$stmt = $this->db->prepare("SELECT * FROM `Users` WHERE $key LIKE ?");
			$stmt->execute(["%" . $value . "%"]);
			return ($stmt->fetchall());
		}
	}

	public function get_users_admin()
	{
		$stmt = $this->db->prepare("SELECT u.*, p.path FROM Users u LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id");
		$stmt->execute();
		return ($stmt->fetchAll());
	}

	public function update_user($user_id, $email, $username, $password)
	{
		$password = hash('whirlpool', $password);
		$stmt = $this->db->prepare("UPDATE Users SET `username` = ?, `email` = ?, `password` = ? WHERE `id` = ?");

		return ($stmt->execute([$username, $email, $password, $user_id]));
	}

	public function update_user_key($user_id, $key, $value)
	{
		$_SESSION[$key] = $value;
		$stmt = $this->db->prepare("UPDATE Users SET $key = ? WHERE `id` = ?");

		return ($stmt->execute([$value, $user_id]));
	}

	public function login($login, $password)
	{
		$hashed = hash('whirlpool', $password);
		$user = $this->get_user('username', $login);

		if ($user['password'] == $hashed) {
			$_SESSION = $user;
			return (true);
		}
		return (false);
	}

	public function kill_user($id)
	{
		$stmt = $this->db->prepare("DELETE FROM Visits WHERE `sender_id` = $id OR `user_id` = $id;
        DELETE FROM Address WHERE `user_id` = $id;
        DELETE FROM Blacklist_entries WHERE `user_id` = $id OR `blacklisted_id` = $id;
        DELETE FROM Chat WHERE `user1_id` = $id OR `user2_id` = $id;
        DELETE FROM Chat_messages WHERE `sender_id` = $id OR `target_id` = $id;
        DELETE FROM Likes WHERE `sender_id` = $id OR `user_id` = $id;
        DELETE FROM Notifications WHERE `sender_id` = $id OR `user_id` = $id;
        DELETE FROM Pictures WHERE `user_id` = $id;
        DELETE FROM Reports WHERE `reported_id` = $id OR `sender_id` = $id;
        DELETE FROM Tag_entries WHERE `user_id` = $id;
        DELETE FROM Visits WHERE `sender_id` = $id OR `user_id` = $id;
        DELETE FROM Users WHERE `id` = $id");
		$stmt->execute();
	}

	public function oauth_login($email)
	{
		$user = $this->get_user('email', $email);
		if (!empty($user) && $user['google'] == 1) {
			$_SESSION = $user;
			return true;
		}
		return false;
	}

	public function oauth_register($user)
	{
		$active_key = md5(microtime(true) * 100000);
		$verif = $this->get_user('email', $user['email']);
		if (!empty($verif)) {
			if ($verif['google'] != 1) {
				return false;
			}
			$this->oauth_login($verif['email']);
		} else {
			$stmt = $this->db->prepare("INSERT INTO `Users`
            (`username`, `email`, `first_name`, `last_name`, `gender`, `lat`, `lng`, `mail_confirm`, `google`)
            VALUES (?, ?, ?, ?, ?, '45.739240', '4.817450', 1, 1)");
			$stmt->execute([$user['name'], $user['email'], $user['givenName'], $user['family_name'], $user['gender']]);
			$this->oauth_login($user['email']);
		}
	}

	public function register($email, $username, $first_name, $last_name, $age, $password)
	{
		$hash_pass = hash('whirlpool', $password);
		$active_key = md5(microtime(true) * 100000);

		$stmt = $this->db->prepare("INSERT INTO `Users`
                  (`id`, `username`, `email`, `first_name`, `last_name`, `age`, `password`, `active_key`)
				  VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)");
		$this->send_confirmation_mail($email, $username, $active_key);
		return ($stmt->execute([$username, $email, $first_name, $last_name, $age, $hash_pass, $active_key]));
	}

	public function changed_mail_email()
	{
		$to = $_SESSION['email'];
		$subject = "Your email has been changed";
		$header = "From: no-reply@z4r6p2.le-101.fr";
		$content = 'Account informations.
        Your email has successfully been changed.
        ---------------
        This mail was send automatically, please do not reply.';
		mail($to, $subject, $content, $header);
	}

	public function send_confirmation_mail($email, $username, $key)
	{
		$to = $email;
		$subject = "Account verification";
		$header = "From: no-reply@z4r6p2.le-101.fr";
		$content = 'Welcome to Matcha.
        To validate your account, please click on the link below or copy it to your navigator.
        http://localhost/index.php/activation?log=' . urlencode($username) . '&key=' . urlencode($key) . '
        ---------------
        This mail was send automatically, please do not reply.';
		mail($to, $subject, $content, $header);
	}

	public function confirm_user($user_id)
	{
		$stmt = $this->db->prepare("UPDATE `Users` SET `mail_confirm` = '1' WHERE `Users`.`id` = ?");
		$stmt->execute([$user_id]);
	}

	public function user_exists($username, $email = null)
	{
		if ($email != null) {
			$query = "SELECT * FROM Users WHERE `username` = '$username' OR `email` = '$email'";
		} else {
			$query = "SELECT * FROM Users WHERE `username` = '$username'";
		}
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$user = $stmt->fetchAll();

		return (!empty($user));
	}

	public function change_password($email)
	{
		if ($user = $this->get_user('email', $email)) {
			$new_password = $this->random_password();
			$hashed = hash('whirlpool', $new_password);
			echo $hashed;

			$stmt = $this->db->prepare("UPDATE Users SET `password` = ? WHERE `email` = ?");
			$stmt->execute([$hashed, $email]);

			$to = $user['email'];
			$subject = 'Password reset request';
			$message = "Your password has been successfully updated.\nPlease log on Matcha with your new password.\nNew password: " . $new_password . " ";
			$headers = 'From: no-reply@z4r6p2.le-101.fr' . "\r\n" .
				'Reply-To: no-reply@matcha.fr' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

			return (mail($to, $subject, $message, $headers));
		}
		return (false);
	}

	private function random_password()
	{
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass);
	}

	public function username_exists($username)
	{
		$user = $this->get_user('username', $username);
		return (!empty($user));
	}

	public function email_exists($email)
	{
		$user = $this->get_user('email', $email);
		return (!empty($user));
	}

	public function check_password($pwd)
	{
		$errors = 0;

		if (strlen($pwd) < 8) {
			$errors++;
		}
		if (!preg_match("#[0-9]+#", $pwd)) {
			$errors++;
		}
		if (!preg_match("#[a-zA-Z]+#", $pwd)) {
			$errors++;
		}
		return ($errors);
	}

	public function get_popular_users()
	{
		$stmt = $this->db->prepare("SELECT u.id, u.username, u.bio, u.popularity_score, p.path, u.lat, u.lng, u.age FROM Users u
		LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id
		LEFT OUTER JOIN Blacklist_entries b ON b.blacklisted_id = u.id AND b.user_id = ?
		WHERE u.id != ? AND b.id IS NULL ORDER BY u.popularity_score DESC LIMIT 21");
		$stmt->execute([$_SESSION['id'], $_SESSION['id']]);
		$users = $stmt->fetchAll();
		$TagModel = new TagModel();
		for ($i = 0; $i < count($users); $i++) {
			$users[$i]['shared_tags'] = $this->get_shared_tags($_SESSION['id'], $users[$i]['id']);
			$users[$i]['tags'] = $TagModel->get_user_tagsName($users[$i]['id']);
		}
		return $users;
	}

	public function custom_search_users($datas)
	{
		if (empty($datas['city'])) {
			$query = "SELECT u.*, a.locality, p.path FROM Users u
			LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id
			LEFT OUTER JOIN Blacklist_entries b ON b.blacklisted_id = u.id AND b.user_id = ?
			INNER JOIN Address a ON a.user_id = u.id
			WHERE u.age >= ? AND u.age <= ? AND u.popularity_score >= ? AND u.popularity_score <= ? AND u.gender = ? AND b.id IS NULL";
			$stmt = $this->db->prepare($query);
			$stmt->execute([$_SESSION['id'], $datas['age_min'], $datas['age_max'], $datas['popularity_min'], $datas['popularity_max'], $datas['gender']]);
		} else {
			$query = "SELECT u.*, p.path, a.locality, p.path FROM Users u
			LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id
			LEFT OUTER JOIN Blacklist_entries b ON b.blacklisted_id = u.id AND b.user_id = ?
			INNER JOIN Address a ON a.user_id = u.id AND a.locality LIKE ?
			WHERE u.age >= ? AND u.age <= ? AND u.popularity_score >= ? AND u.popularity_score <= ? AND u.gender = ? AND b.id IS NULL AND u.id != ?";
			$stmt = $this->db->prepare($query);
			$stmt->execute([$_SESSION['id'], "%" . $datas['city'] . "%", $datas['age_min'], $datas['age_max'], $datas['popularity_min'], $datas['popularity_max'], $datas['gender'], $_SESSION['id']]);
		}
		$users = $stmt->fetchAll();
		$TagModel = new TagModel;
		for ($i = 0; $i < count($users); $i++) {
			$users[$i]['shared_tags'] = $this->get_shared_tags($_SESSION['id'], $users[$i]['id']);
			$users[$i]['tags'] = $TagModel->get_user_tagsName($users[$i]['id']);
		}
		return $users;
	}

	public function suggested_users()
	{
		$agemin = $_SESSION['age'] - 5;
		$agemax = $_SESSION['age'] + 5;

		if ($_SESSION['target_gender'] != "") {
			$stmt = $this->db->prepare("SELECT u.*, p.path, a.locality, p.path FROM Users u
			LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id
			INNER JOIN Address a ON a.user_id = u.id
			LEFT OUTER JOIN Blacklist_entries b ON b.blacklisted_id = u.id AND b.user_id = ?
			WHERE b.id IS NULL AND u.age >= ? AND u.age <= ? AND u.gender = ? AND u.target_gender = ? AND u.id != ? ORDER BY u.popularity_score DESC");
			$stmt->execute([$_SESSION['id'], $agemin, $agemax, $_SESSION['target_gender'], $_SESSION['gender'], $_SESSION['id']]);
			$users = $stmt->fetchAll();
		} else {
			$stmt = $this->db->prepare("SELECT u.*, p.path, a.locality, p.path FROM Users u
			LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id
			INNER JOIN Address a ON a.user_id = u.id
			LEFT OUTER JOIN Blacklist_entries b ON b.blacklisted_id = u.id AND b.user_id = ?
			WHERE b.id IS NULL AND u.age >= ? AND u.age <= ? AND u.gender LIKE ? AND u.target_gender = ?AND u.id != ? ORDER BY u.popularity_score DESC");
			$stmt->execute([$_SESSION['id'], $agemin, $agemax, '%' . $_SESSION['target_gender'] . '%', $_SESSION['gender'], $_SESSION['id']]);
			$users = $stmt->fetchAll();
		}

		$TagModel = new TagModel;
		for ($i = 0; $i < count($users); $i++) {
			$users[$i]['shared_tags'] = $this->get_shared_tags($_SESSION['id'], $users[$i]['id']);
			$users[$i]['tags'] = $TagModel->get_user_tagsName($users[$i]['id']);
		}
		return $users;
	}

	public function custom_suggested_users($datas)
	{
		if ($_SESSION['target_gender']) {
			$stmt = $this->db->prepare("SELECT u.*, p.path, a.locality, p.path FROM Users u
			LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id
			INNER JOIN Address a ON a.user_id = u.id
			LEFT OUTER JOIN Blacklist_entries b ON b.blacklisted_id = u.id AND b.user_id = ?
			WHERE b.id IS NULL AND u.age >= ? AND u.age <= ? AND u.gender = ? AND u.target_gender = ? AND a.locality LIKE ?
			AND u.popularity_score > ? AND u.popularity_score < ?
			ORDER BY u.popularity_score DESC");
			$stmt->execute([$_SESSION['id'], $datas['age_min'], $datas['age_max'], $_SESSION['target_gender'], $_SESSION['gender'], "%" . $datas['city'] . "%", $datas['popularity_min'], $datas['popularity_max']]);
			$users = $stmt->fetchAll();
		} else {
			$stmt = $this->db->prepare("SELECT u.*, p.path, a.locality, p.path FROM Users u
			LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id
			INNER JOIN Address a ON a.user_id = u.id
			LEFT OUTER JOIN Blacklist_entries b ON b.blacklisted_id = u.id AND b.user_id = ?
			WHERE b.id IS NULL AND u.age >= ? AND u.age <= ? AND u.target_gender = ? AND a.locality LIKE ?
			AND u.popularity_score > ? AND u.popularity_score < ?
			ORDER BY u.popularity_score DESC");
			$stmt->execute([$_SESSION['id'], $datas['age_min'], $datas['age_max'], $_SESSION['gender'], "%" . $datas['city'] . "%", $datas['popularity_min'], $datas['popularity_max']]);
			$users = $stmt->fetchAll();
		}

		$TagModel = new TagModel;
		for ($i = 0; $i < count($users); $i++) {
			$users[$i]['shared_tags'] = $this->get_shared_tags($_SESSION['id'], $users[$i]['id']);
			$users[$i]['tags'] = $TagModel->get_user_tagsName($users[$i]['id']);
		}
		return $users;
	}

	public function check_profile_complete($userid)
	{
		$stmt = $this->db->prepare("SELECT u.*, (SELECT COUNT(*) FROM Pictures p
		WHERE p.user_id = u.id) AS pictures_count,
		(SELECT COUNT(*) FROM Tag_entries t WHERE t.user_id = u.id) AS tags_count, (SELECT COUNT(*) FROM `Address` a WHERE a.user_id = u.id) as address_count FROM Users u WHERE u.id = ?");
		$stmt->execute([$userid]);
		$user = $stmt->fetch();
		if (!empty($user['gender']) && !empty($user['bio']) && $user['address_count'] > 0 && $user['tags_count'] > 0 && $user['pictures_count'] > 0) {
			$this->update_user_key($userid, 'profile_complete', 1);
		} else {
			$this->update_user_key($userid, 'profile_complete', 0);
		}
	}

	public function get_shared_tags($userId1, $userId2)
	{
		$stmt = $this->db->prepare("SELECT t.name FROM Tags t
		INNER JOIN Tag_entries te ON te.tag_id = t.id AND te.user_id = ?
		INNER JOIN Tag_entries te2 ON te2.tag_id = t.id AND te2.user_id = ?");
		$stmt->execute([$userId1, $userId2]);
		return $stmt->fetchAll();
	}

	public function ban_user($userid, $time)
	{
		$stmt = $this->db->prepare("UPDATE Users SET `banned` = NOW() + INTERVAL ? HOUR WHERE id = ?");
		$stmt->execute([$time, $userid]);
	}

	public function get_banned_users()
	{
		$stmt = $this->db->prepare("SELECT * FROM Users WHERE `banned` IS NOT NULL ORDER BY `banned`");
		$stmt->execute();
		return $stmt->fetchAll();
	}
}
