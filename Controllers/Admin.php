<?php

class Admin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('UserModel');
		$this->loadModel('ReportModel');
		$this->loadModel('BlacklistModel');
    }

    public function index()
    {
		if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
            include '404.php';
            die;
        }
        $this->loadModel('HydrateModel');
        $data = array(
            'users' => $this->UserModel->get_users_admin(),
            'reports' => $this->ReportModel->get_reports(),
			'stats' => $this->HydrateModel->get_stats(),
			'blacklists' => $this->BlacklistModel->get_blacklist_entries(),
			'banned' => $this->UserModel->get_banned_users()
        );

        $this->loadView('templates/header');
        $this->loadView('Admin/index', $data);
        $this->loadView('templates/footer');
    }

    public function hydratation()
    {
        $this->loadModel('HydrateModel');
        if (!$_POST['number']) {
            $number = 1;
        } else {
			$number = $_POST['number'];
		}
		$this->HydrateModel->generate_user($number);
		echo "ok";
    }

    public function members()
    {
        if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
            include '404.php';
        } else {

            $this->loadModel('LikeModel');

            $data = array(
                'users' => $this->UserModel->get_users_admin(),
            );

            $this->loadView('templates/header');
            $this->loadView('admin/members', $data);
            $this->loadView('templates/footer');
        }
    }

    public function kill_user()
    {
		if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
            include '404.php';
            die;
        }
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            if ($_SESSION['role'] == "admin") {
                $this->UserModel->kill_user($_POST['id']);
            }
        }
	}

	public function ban_user()
	{
		if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
            include '404.php';
            die;
        }
		if (isset($_POST['time']) && is_numeric($_POST['time']) && $_POST['time'] >= 0 && isset($_POST['userid']) && is_numeric($_POST['userid']) && $_POST['userid'] > 0)
		{
			$this->UserModel->ban_user($_POST['userid'], $_POST['time']);
			header('Location: /index.php/admin');
		}
		header('Location: /index.php/admin');
	}

	public function unban_user($userid = null)
	{
		if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
            include '404.php';
            die;
        }
		if (isset($userid) && is_numeric($userid) && $userid > 0)
		{
			$this->UserModel->update_user_key($userid, 'banned', null);
			header('Location: /index.php/admin');
		}
		header('Location: /index.php/admin');
	}
	
	public function liveuser()
	{
		if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
            include '404.php';
            die;
        }
		if (isset($_GET['username'])) {
			$username = trim(addslashes(htmlspecialchars($_GET['username'])));
			$users = $this->UserModel->get_live_user('username', $username);
			echo json_encode($users);
		}
	}
}