<?php

class User extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('UserModel');
    }

    public function disconnect()
    {
        session_destroy();
        header('Location: /');
    }

    public function forgot_password()
    {
        $data = array();

        if (isset($_POST['email']) && isset($_POST['token'])
            && $_POST['token'] == $_SESSION['token']) {
            $this->loadModel('UserModel');
            // Protection CRLF, retours chariots dans input
            $mail = trim(str_replace(array("\n", "\r", PHP_EOL), '', htmlspecialchars($_POST['email'])));
            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                if ($this->UserModel->change_password($mail)) {
                    session_destroy();
                    header('Location: /index.php/user/password_reset');
                } else {
                    $data['error'] = 'An error occured. Please try again.';
                }
            }
        }
        $this->loadView('templates/header');
        $this->loadView('user/forgot_password', $data);
        $this->loadView('templates/footer');
    }

    public function password_reset()
    {
        $this->loadView('templates/header');
        $this->loadView('user/password_reset');
        $this->loadView('templates/footer');
    }

    public function delete_picture()
    {
        $this->loadModel('PictureModel');
        if (isset($_POST) && !empty($_POST)) {
            $picture = $this->PictureModel->get_picture('id', $_POST['id']);
            if ($picture && $picture['user_id'] == $_SESSION['id']) {
                $this->PictureModel->delete_picture('id', $picture['id']);
                $this->UserModel->check_profile_complete($_SESSION['id']);
            }
        } else {
            header('Location: /404.php');
        }
    }

    public function set_profile_picture()
    {
        $this->loadModel('PictureModel');
        $this->loadModel('UserModel');
        if (isset($_POST) && !empty($_POST)) {
            $picture = $this->PictureModel->get_picture('id', $_POST['id']);
            if ($picture && $picture['user_id'] == $_SESSION['id']) {
                $this->UserModel->update_user_key($_SESSION['id'], 'profile_pic_id', $_POST['id']);
                echo ($picture['path']);
            }
        } else {
            header('Location: /404.php');
        }
    }

    public function save_coordinate()
    {
        if (isset($_POST) && !empty($_POST)) {
            $data['coord'] = "Lat : " . $_POST['lat'] . " - Long : " . $_POST['long'];
        }
        $this->loadView('test', $data);
    }

    public function save_location()
    {
        $this->loadModel('UserModel');
        $this->loadModel('AddressModel');
        if (isset($_POST) && !empty($_POST)) {
            $datas = $this->UserModel->get_address($_POST['lat'], $_POST['lng']);
            $res = $datas['results'][0];
            $components = $res['address_components'];
            $address = $this->AddressModel->get_address('user_id', $_SESSION['id']);
            if ($address) {
                if ($address['source'] == "remote_addr" || $address['source'] == "js") {
                    $this->AddressModel->delete_address('user_id', $_SESSION['id']);
                    $this->AddressModel->new_address($_SESSION['id'], $res['formatted_address'], $components[0]['long_name'], $components[1]['long_name'], $components[2]['long_name'], $components[5]['long_name'], $components[6]['long_name'], "js");
                }
            } else {
                $this->AddressModel->new_address($_SESSION['id'], $res['formatted_address'], $components[0]['long_name'], $components[1]['long_name'], $components[2]['long_name'], $components[5]['long_name'], $components[6]['long_name'], "js");
            }
            echo "ok";
        }
    }

    public function get_theme()
    {
        if (isset($_SESSION) && $_SESSION['theme'] == "0") {
            echo "light";
        } else {
            echo "dark";
        }
    }

    public function set_theme()
    {
        $this->loadModel('UserModel');
        if (isset($_SESSION) && $_POST) {
            if ($_POST['theme'] == "dark") {
                $this->UserModel->update_user_key($_SESSION['id'], 'theme', '1');
            } else if ($_POST['theme'] == "light") {
                $this->UserModel->update_user_key($_SESSION['id'], 'theme', '0');
            }
        }
    }

    public function like_user()
    {
		$this->loadModel('LikeModel');
		$this->loadModel('PictureModel');
        if (isset($_POST) && $_POST['sender'] == $_SESSION['id']) {
			$sender_pics = $this->PictureModel->get_pictures('user_id', $_POST['sender']);
			$user_pics = $this->PictureModel->get_pictures('user_id', $_POST['user_id']);

			if (count($user_pics) > 0 && count($sender_pics) > 0) {
	            if ($this->LikeModel->user_liked($_POST['sender'], $_POST['user_id'])) {
	                $this->LikeModel->unlike_user($_POST['sender'], $_POST['user_id']);
	                echo "unliked";
	            } else {
	                $this->LikeModel->like_user($_POST['sender'], $_POST['user_id']);
	                if ($this->get_match($_POST['sender'], $_POST['user_id'])) {
	                    echo "match";
	                } else {
	                    echo "liked";
	                }
				}
			}
        }
    }

    public function get_match($sender, $user_id)
    {
        $this->loadModel('LikeModel');
        return ($this->LikeModel->user_liked($sender, $user_id) && $this->LikeModel->user_liked($user_id, $sender));
    }

    public function blacklist()
    {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $this->loadModel('BlacklistModel');

            $this->BlacklistModel->new_blacklist_entry($_SESSION['id'], $_POST['id']);
        }
    }

    public function unblacklist()
    {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $this->loadModel('BlacklistModel');

            $this->BlacklistModel->unblacklist_user($_SESSION['id'], $_POST['id']);
        }
    }

    public function report_user()
    {
        $this->loadModel('ReportModel');
        if (isset($_POST['userId']) && isset($_POST['targetId'])) {
            if ($this->ReportModel->report_exists($_POST['userId'], $_POST['targetId']) == false) {
                $this->ReportModel->new_report($_POST['userId'], $_POST['targetId']);
                echo "ok";
            } else {
                echo "ko";
            }
        }
    }
}