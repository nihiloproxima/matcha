<?php

class Profile extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->loadModel('UserModel');
		$this->loadModel('LikeModel');
		$this->loadModel('PictureModel');
		$this->loadModel('TagModel');
		$this->loadModel('VisitModel');
		$this->loadModel('AddressModel');
		$this->loadModel('BlacklistModel');
		$this->loadModel('NotificationModel');
		$this->loadModel('ReportModel');
		if (!isset($_SESSION['username'])) {
			header('Location: /');
		}
	}

	public function index($username = null)
	{
		/*
         * Define who is the requested user
         * Retrieve all the informations for the specified user
         */
		if ($username) {
			if ($this->UserModel->user_exists(htmlspecialchars($username))) {
				$user = $this->UserModel->get_user('username', htmlspecialchars($username));
			} else {
				include '404.php';
				die;
			}
		} else {
			$user = $this->UserModel->get_user('id', $_SESSION['id']);
		}
		/* Create a new Visit if user in on another profile, there is a 100 seconds delay */
		if ($user['id'] != $_SESSION['id'] && $this->BlacklistModel->is_blacklisted_by($user['id'], $_SESSION['id']) == false) {
			$last_visit = $this->VisitModel->get_last_visit($_SESSION['id'], $user['id']);
			if ($last_visit) {
				if (-(strtotime($last_visit['creation_date']) - time() + 3600) > 100) {
					$this->VisitModel->update_visit($_SESSION['id'], $user['id']);
				}
			} else {
				$this->VisitModel->new_visit($_SESSION['id'], $user['id']);
			}
			$visit = $this->NotificationModel->create_notification($user, $_SESSION['id'], "visit");
		}
		$likes = $this->LikeModel->get_likes('user_id', $user['id']);
		$pictures = $this->PictureModel->get_pictures('user_id', $user['id']);

		$data = array(
			'user' => $user,
			'pictures' => $pictures,
			'profile_pic' => $this->PictureModel->get_picture('id', $user['profile_pic_id']),
			'likes' => $likes,
			'address' => $this->AddressModel->get_address('user_id', $user['id']),
			'is_blacklisted' => $this->BlacklistModel->is_blacklisted_by($_SESSION['id'], $user['id']),
			'is_reported' => $this->ReportModel->is_reported($_SESSION['id'], $user['id']),
			'has_photo' => $this->PictureModel->has_photo('user_id', $_SESSION['id']),
		);
		if ($data['user']['id'] != $_SESSION['id']) {
			$data['user_liked'] = $this->LikeModel->user_liked($user['id'], $_SESSION['id']);
			$data['current_user_liked'] = $this->LikeModel->user_liked($_SESSION['id'], $user['id']);
		}
		if (empty($data['profile_pic'])) {
			$data['profile_pic']['path'] = "assets/uploads/default_user.jpeg";
		}
		$tag_entries = $this->TagModel->get_tag_entries('user_id', $user['id']);
		foreach ($tag_entries as $tag) {
			$data['user_tags'][] = $this->TagModel->get_tag('id', $tag['tag_id']);
		}

		$this->loadView('templates/header', $data);
		$this->loadView('Profile/index', $data);
		$this->loadView('templates/footer');
		if ($username != $_SESSION['username'] && !empty($visit)) {
			echo '<script>socket.emit("send-notification",' . json_encode($visit) . ');</script>';
		}
	}

	public function edit($error = null)
	{
		if (!isset($_SESSION['id'])) {
			header('Location: /');
		}
		$data['user'] = $this->UserModel->get_user('id', $_SESSION['id']);
		$data['pictures'] = $this->PictureModel->get_pictures('user_id', $_SESSION['id']);
		$user_tags = $this->TagModel->get_tag_entries('user_id', $_SESSION['id']);
		foreach ($user_tags as $tag) {
			$data['user_tags'][] = $tag['tag_id'];
		}
		$data['tags'] = $this->TagModel->get_all_tags();
		$data['address'] = $this->AddressModel->get_address('user_id', $_SESSION['id']);
		if (isset($error) && !empty($error)) {
			$data['error'] = $error;
		}

		$this->loadView('templates/header');
		$this->loadView('profile/edit', $data);
		$this->loadView('templates/footer');
		echo '<script src="/assets/js/account_settings.js"></script>';
	}

	public function settings($error = null)
	{
		$data = array();
		if ($error) {
			$data['error'] = $error;
		}

		$this->loadView('templates/header');
		$this->loadView('profile/settings', $data);
		$this->loadView('templates/footer');
		echo '<script src="/assets/js/account_settings.js"></script>';
	}

	public function activity()
	{
		if (!isset($_SESSION['id'])) {
			header('Location: /index.php/login');
		}

		$data = array(
			'likes' => $this->LikeModel->get_users_who_liked($_SESSION['id']),
			'user_likes' => $this->LikeModel->get_user_likes($_SESSION['id']),
			'blacklist' => $this->BlacklistModel->get_blacklisted_users($_SESSION['id']),
			'matches' => $this->LikeModel->get_user_matches($_SESSION['id']),
			'visits' => $this->VisitModel->get_users_who_visited($_SESSION['id']),
		);

		$this->loadView('templates/header');
		$this->loadView('profile/activity', $data);
		$this->loadView('templates/footer');
		echo '<script src="/Assets/js/activity.js"></script>';
	}

	public function edit_account_settings()
	{
		$data = array(
			'error' => '',
			'success' => '',
			'notifications' => ($_SESSION['notification_mails'] == 0 ? "unchecked" : "checked"),
			'username' => $_SESSION['username'],
			'email' => $_SESSION['email'],
		);
		$error = 0;
		// Change username if set, not empty, and different from current
		if (isset($_POST['username']) && !empty($_POST['username']) && $_POST['username'] != $_SESSION['username']) {
			$data['username'] = trim(htmlspecialchars($_POST['username']));
			if ($this->UserModel->username_exists($data['username']) && $data['username'] != $_SESSION['username']) {
				$data['error'] .= "Username already taken. ";
				return $this->settings($data['error']);
			} else {
				$this->UserModel->update_user_key($_SESSION['id'], 'username', $data['username']);
				$error--;
			}
		}
		// Change user's first_name && last_name
		if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
			$data['first_name'] = trim(htmlspecialchars($_POST['first_name']));
			$this->UserModel->update_user_key($_SESSION['id'], 'first_name', $data['first_name']);
			$error--;
		}
		if (isset($_POST['last_name']) && $_POST['last_name'] != $_SESSION['last_name']) {
			$data['last_name'] = trim(htmlspecialchars($_POST['last_name']));
			$this->UserModel->update_user_key($_SESSION['id'], 'last_name', $data['last_name']);
			$error--;
		}
		// Change email if set, not empty, and different from current
		if (isset($_POST['email']) && !empty($_POST['email']) && $_POST['email'] != $_SESSION['email']) {
			if (preg_match("/(.+)@(.+).(.+)/", $_POST['email'])) {
				$data['email'] = trim(htmlspecialchars($_POST['email']));
				if ($this->UserModel->email_exists($data['email']) && $data['email'] != $_SESSION['email']) {
					$data['error'] .= "Email already taken. ";
					return $this->settings($data['error']);
				} else {
					$this->UserModel->update_user_key($_SESSION['id'], 'email', $data['email']);
					$this->UserModel->login($data['username'], $_SESSION['password']);
					$this->UserModel->changed_mail_email();
					$error--;
				}
			} else {
				$data['error'] .= "Not a valid email";
				return $this->settings($data['error']);
			}
		}
		// Change password if set, not empty and corresponds
		if (
			isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['password_confirm'])
			&& !empty($_POST['old_password']) && !empty($_POST['new_password']) && !empty($_POST['password_confirm'])
		) {
			$data['old_password'] = trim(htmlspecialchars($_POST['old_password']));
			$data['new_password'] = trim(htmlspecialchars($_POST['new_password']));
			$data['password_confirm'] = trim(htmlspecialchars($_POST['password_confirm']));

			$user = $this->UserModel->get_user('username', $data['username']);
			$hash = hash('whirlpool', $data['old_password']);
			$pass_error = 0;

			if ($hash !== $user['password']) {
				$data['error'] .= "Your old password is wrong. ";
				$pass_error++;
			}
			if ($data['new_password'] !== $data['password_confirm']) {
				$data['error'] .= "Passwords don't match. ";
				$pass_error++;
			}
			if ($pass_error == 0) {
				$hash = hash('whirlpool', $data['new_password']);
				$this->UserModel->update_user_key($user['id'], 'password', $hash);
				$this->UserModel->login($data['username'], $data['new_password']);
			} else {
				$data['error'] .= "Not cool.";
				return $this->settings($data['error']);
			}
		}
		// Checkbox for notifications mails
		if (isset($_POST['notifications'])) {
			$this->UserModel->update_user_key($_SESSION['id'], 'notification_mails', '1');
		} else {
			$this->UserModel->update_user_key($_SESSION['id'], 'notification_mails', '0');
		}
		if ($error < 0) {
			$this->loadView('Templates/header');
			$this->loadView('Profile/success');
			$this->loadView('Templates/footer');
		} else {
			$this->settings($data['error']);
		}
	}

	public function update_profile_picture()
	{
		$this->loadModel('UserModel');
		if (isset($_POST['image'])) {
			$type = mime_content_type($_POST['image']);

			if ($type == "image/png" || $type == "image/jpeg") {
				$this->UserModel->update_user_key($_SESSION['id'], 'profil_pic', $_POST['image']);
			}
			header('Location: /index.php/profile');
		}
	}

	public function edit_public_informations()
	{
		if (isset($_POST) && !empty($_POST)) {
			$data = array(
				'gender' => $_POST['gender'],
				'target_gender' => $_POST['target_gender'],
				'bio' => trim(htmlspecialchars($_POST['bio'])),
				'age' => $_POST['age'],
			);
			// Handle user tags, flush it then refill with new ones
			$this->TagModel->flush_user_tags('user_id', $_SESSION['id']);
			if (isset($_POST['tags'])) {

				foreach ($_POST['tags'] as $tag) {
					$tag = trim(htmlspecialchars(str_replace(' ', '', $tag)));
					$tag = $tag[0] != "#" ? "#" . $tag : $tag;

					if ($this->TagModel->get_tag('name', $tag) == false) {
						$this->TagModel->create_tag($tag);
					}
					$this->TagModel->add_tag_entry($_SESSION['id'], $tag);
				}
			}
			$this->UserModel->update_user_key($_SESSION['id'], 'gender', $data['gender']);
			$this->UserModel->update_user_key($_SESSION['id'], 'age', $data['age']);
			$this->UserModel->update_user_key($_SESSION['id'], 'target_gender', $data['target_gender']);
			$this->UserModel->update_user_key($_SESSION['id'], 'bio', $data['bio']);

			if (isset($_FILES) && !empty($_FILES) && count($_FILES) > 0) {
				$pictures = $this->PictureModel->get_pictures('user_id', $_SESSION['id']);
				if (count($pictures) + count($_FILES) <= 5) {
					$this->PictureModel->save_user_pictures();
				} else {
					$data['error'] = "You already have reached your maximum uploaded pictures. Please delete your old pictures to save new ones!";
				}
			}
			if (
				isset($_POST['formatted_address']) && isset($_POST['street_number']) && isset($_POST['route']) && isset($_POST['locality'])
				&& isset($_POST['country']) && isset($_POST['postal_code']) && !empty($_POST['formatted_address'])
			) {
				if ($this->AddressModel->get_address('user_id', $_SESSION['id'])) {
					$this->AddressModel->delete_address('user_id', $_SESSION['id']);
				}
				$this->AddressModel->new_address(
					$_SESSION['id'],
					$_POST['formatted_address'],
					$_POST['street_number'],
					$_POST['route'],
					$_POST['locality'],
					$_POST['country'],
					$_POST['postal_code'],
					"user"
				);
				$this->UserModel->update_user_key($_SESSION['id'], 'lat', $_POST['lat']);
				$this->UserModel->update_user_key($_SESSION['id'], 'lng', $_POST['lng']);
			}
			if (isset($data['error'])) {
				$this->edit($data['error']);
			}
			$this->UserModel->check_profile_complete($_SESSION['id']);
			header('Location: /index.php/profile');
		} else {
			header('Location: /404.php');
		}
	}

	public function get_match($sender, $user_id)
	{
		return ($this->LikeModel->user_liked($sender, $user_id) && $this->LikeModel->user_liked($user_id, $sender));
	}
}
