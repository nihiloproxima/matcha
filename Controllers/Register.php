<?php

class Register extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('UserModel');
    }

    public function index($error = null)
    {
        $data = array();
        // Redirect if user is logged on
        if (isset($_SESSION['username'])) {
            header('Location: /');
        }

        if (isset($error)) {
            $data['error'] = $error;
        }
        // Check if post has been requested
        else if (
            isset($_POST['username']) && isset($_POST['email']) &&
            isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['age']) && is_numeric($_POST['age']) &&
            isset($_POST['password']) && isset($_POST['password_confirm'])
        ) {
            $this->loadModel('UserModel');

            $data = array(
                'username' => trim(addslashes(htmlspecialchars($_POST['username']))),
                'email' => trim(addslashes(htmlspecialchars($_POST['email']))),
                'first_name' => ucfirst(strtolower(trim(addslashes(htmlspecialchars($_POST['first_name']))))),
                'last_name' => ucfirst(strtolower(trim(addslashes(htmlspecialchars($_POST['last_name']))))),
                'age' => $_POST['age'],
                'password' => trim(addslashes(htmlspecialchars($_POST['password']))),
                'password_confirm' => trim(addslashes(htmlspecialchars($_POST['password_confirm']))),
                'error' => '',
            );

            if (
                empty($data['username']) || empty($data['email']) ||
                empty($data['first_name']) || empty($data['last_name']) ||
                empty($data['password']) || empty($data['password'])
            ) {
                $data['error'] .= "Missing fields in form.";
            } else if ($this->UserModel->user_exists($data['username'], $data['email']) === true) {
                $data['error'] .= "Email or login already taken.";
            } else if ($this->UserModel->check_password($data['password']) != 0) {
                $data['error'] .= "The password is not secure enough. <br />It must be at least 8 characters long, contain one letter letter and one number.<br /> Yeah that sucks.";
            } else if ($data['password'] !== $data['password_confirm']) {
                $data['error'] .= "Passwords don't match.";
            } else if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
                $data['error'] .= "Captcha verifiation failed.";
             } else if ($this->UserModel->register($data['email'], $data['username'], $data['first_name'], $data['last_name'], $data['age'], $data['password']) == false) {
                $data['error'] .= "An error occured on the server, please try again later.";
            } else if (empty($data['error'])) {
                // Register success
                header('Location: /index.php/register/confirm');
            }
        }
        $this->loadView('Templates/header');
        $this->loadView('Register/index', $data);
        $this->loadView('Templates/footer');
    }

    public function confirm()
    {
        $this->loadView('templates/header');
        $this->loadView('register/confirm');
        $this->loadView('templates/footer');
    }

    public function google_register()
    {
        require_once 'vendor/autoload.php';

        $client = new Google_Client();
        $client->setAuthConfig('client_credentials.json');
        $client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

        if (isset($_SESSION['access_token']) && $_SESSION['access_token'] && isset($_SESSION['id'])) {
            header('Location: /');
        } else {
            $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php/register/google_redirect';
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }
    }

    public function google_redirect()
    {
        require_once 'vendor/autoload.php';

        $client = new Google_Client();
        $client->setAuthConfigFile('client_credentials.json');
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/index.php/register/google_redirect');
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
            if ($this->UserModel->oauth_register($user)) {
                header('Location: /index.php/profile');
            } else {
                $error = "There's a problem with your OAuth, you either already have an account with your Google email. Or something bad happened...";
                $this->index($error);
            }
        }
    }
}
