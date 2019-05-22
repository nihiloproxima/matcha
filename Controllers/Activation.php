<?php

class Activation extends Controller
{
    public function index()
    {
        if (isset($_GET['log']) && isset($_GET['key']))
        {
            $this->loadModel('UserModel');
            $data = array();
            $login = trim(addslashes(htmlspecialchars(urldecode($_GET['log']))));
            $key = trim(addslashes(htmlspecialchars($_GET['key'])));
            if ($this->UserModel->user_exists($login)) {
                $user = $this->UserModel->get_user('username', $login);
                if ($user['active_key'] == $key) {
                    // If not confirmed, confirm user
                    if ($user['mail_confirm'] == 0) {
                        $this->UserModel->confirm_user($user['id']);
                        $data['success'] = "Your email has been successfully confirmed. Please log-in.";
                    } else { // User already confirmed
                        $data['already_confirmed'] = "This email is already confirmed. Please <a href='index.php/login'>log-in</a>.";
                    }
                } else { // Wrong activation key
                    $data['error'] = "There was a problem with your activation. You may not have the right activation key. Please verify the link in your email box.";
                }
            }
            $this->loadView('templates/header');
            $this->loadView('activation/index', $data);
            $this->loadView('templates/footer');
        } else {
            include('404.php');
        }
    }

    public function resend()
    {
        $this->loadModel('UserModel');
        $data = array();
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $data['email'] = trim(htmlspecialchars($_POST['email']));
            if ($this->UserModel->user_exists('', $data['email'])) {
                $user = $this->UserModel->get_user('email', $data['email']);
                $this->UserModel->send_confirmation_mail($user['email'], $user['username'], $user['active_key']);
                $data['email_sended'] = "A new activation mail has been sent, please check your email box.";
            } else {
                $data['error'] = "There is no account with this email adress.";
            }
        }
        $this->loadView('templates/header');
        $this->loadview('activation/resend', $data);
        $this->loadView('templates/footer');
    }
}