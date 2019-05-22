<?php

class Login extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->loadModel('UserModel');
		$this->loadModel('AddressModel');
	}

	public function index($error = null)
	{
		if (isset($_SESSION['username'])) {
			header('Location: ' . WEBROOT);
		}
		if ($error) {
			$data['error'] = $error;
			$this->loadView('templates/header');
			$this->loadView('Login/index', $data);
			$this->loadView('Templates/footer');
			die;
		}
		$data = array(
			'error' => "",
			'username' => "",
			'password' => "",
		);
		if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['token']) && $_POST['token'] == $_SESSION['token']) {
			$data['username'] = trim(addslashes(htmlspecialchars($_POST['username'])));
			$data['password'] = trim(addslashes(htmlspecialchars($_POST['password'])));

			if ($this->UserModel->user_exists($data['username']) == false) {
				$data['error'] = 'Invalid credentials.';
			} else {
				$user = $this->UserModel->get_user('username', $data['username']);
				if ($user['mail_confirm'] == 0) {
					header('Location: /index.php/register/confirm');
				} else if ($this->UserModel->login($user['username'], $data['password']) == false) {
					$data['error'] = "Wrong login or password.";
				} else {
					$address = $this->AddressModel->get_address('user_id', $_SESSION['id']);
					if (!$address) {
						$metadatas = $this->UserModel->get_location();
						$this->AddressModel->force_new_address($metadatas);
					}
					if (-(strtotime($user['banned']) - time() + 7200) < 0) {
						session_destroy();
						header('Location: /index.php/login/banned');
					} else {
						header('Location: /index.php/profile');
					}
				}
			}
		}
		$this->loadView('Templates/header');
		$this->loadView('Login/index', $data);
		$this->loadView('Templates/footer');
	}

	public function google_login()
	{
		require_once 'vendor/autoload.php';

		$client = new Google_Client();
		$client->setAuthConfig('client_credentials.json');
		$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
			if (isset($_SESSION['id'])) {
				if (-(strtotime($user['banned']) - time() + 7200) < 0) {
					session_destroy();
					header('Location: /index.php/login/banned');
				} else {
					header('Location: /index.php/profile');
				}
			} else {
				$error = "No google account with your credentials. Have you registered first ?";
				$this->index($error);
			}
		} else {
			$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php/login/google_redirect';
			header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		}
	}

	public function google_redirect()
	{
		require_once 'vendor/autoload.php';

		$client = new Google_Client();
		$client->setAuthConfigFile('client_credentials.json');
		$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/index.php/login/google_redirect');
		$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
		$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

		if (!isset($_GET['code'])) {
			$auth_url = $client->createAuthUrl();
			header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
		} else {
			$client->authenticate($_GET['code']);
			$_SESSION['access_token'] = $client->getAccessToken();
			$client->setAccessToken($_SESSION['access_token']);
			$oauth2 = new Google_Service_OAuth2($client);
			$user = $oauth2->userinfo->get();
			if ($this->UserModel->oauth_login($user['email'])) {
				$address = $this->AddressModel->get_address('user_id', $_SESSION['id']);
				if (!$address) {
					$metadatas = $this->UserModel->get_location();
					$this->AddressModel->force_new_address($metadatas);
				}
				if (-(strtotime($user['banned']) - time() + 7200) < 0) {
					session_destroy();
					header('Location: /index.php/login/banned');
				} else {
					header('Location: /index.php/profile');
				}
			} else {
				header('Location: /index.php/login');
			}
		}
	}

	public function banned()
	{
		$this->loadView('templates/header');
		$this->loadView('login/banned');
		$this->loadView('templates/footer');
	}
}
