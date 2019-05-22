<?php

class Notification extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('NotificationModel');
        $this->loadModel('UserModel');
        $this->loadModel('BlacklistModel');
    }

    public function index()
    {
        $this->loadModel('NotificationModel');

        $data = array(
            'notifications' => $this->NotificationModel->get_user_notifications('user_id', $_SESSION['id']),
        );
        $this->loadView('templates/header');
        $this->loadView('Notification/index', $data);
        $this->loadView('templates/footer');
    }

    public function new_notification()
    {
        if (isset($_POST['sender']) && isset($_POST['user_id']) && isset($_POST['type'])) {
            $user = $this->UserModel->get_user('id', $_POST['user_id']);
            if (!$this->BlacklistModel->is_blacklisted_by($user['id'], $_SESSION['id'])) {
                echo json_encode($this->NotificationModel->create_notification($user, $_POST['sender'], $_POST['type']));
            }
        }
    }

    public function delete()
    {
        if (isset($_POST['id'])) {
            $notif = $this->NotificationModel->get_notification('id', $_POST['id']);
            if (!empty($notif) && $notif['user_id'] == $_SESSION['id']) {
                $this->NotificationModel->delete_notification($notif['id']);
            }
        }
    }

    public function read_notification()
    {
        if (isset($_POST['id'])) {
            $this->NotificationModel->update_notification($_POST['id'], 'status', 'read');
        }
    }
}
