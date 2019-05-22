<?php

class Chat extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['id'])) {
            header('Location: /index.php');
            die;
        }
        $this->loadModel('ChatModel');
    }

    public function index()
    {
        $this->loadModel('PictureModel');

        $data = array(
            'chats' => $this->ChatModel->get_user_chats($_SESSION['id']),
            'picture' => $this->PictureModel->get_picture('user_id', $_SESSION['id']),
        );

        $this->loadView('templates/header');
        $this->loadView('chat/index', $data);
        $this->loadView('templates/footer');
        echo '<script src="/assets/js/chat.js"></script>';
    }

    public function send_message()
    {
        if (isset($_POST['message'])) {
            $datas = array (
                'chat_id' => trim(addslashes(htmlspecialchars($_POST['chat_id']))),
                'id' => trim(addslashes(htmlspecialchars($_POST['id']))),
                'target_id' => trim(addslashes(htmlspecialchars($_POST['target_id']))),
                'content' => trim(addslashes(htmlspecialchars($_POST['content'])))
            );
            $this->ChatModel->send_message($datas['chat_id'], $datas['id'], $datas['target_id'], $datas['content']);
            return (200);
        }
        return (401);

    }

    public function get_chat_infos()
    {
        if (isset($_GET['chatroom_id']) && is_numeric($_GET['chatroom_id'])) {
            $chat = $this->ChatModel->get_chat_infos($_GET['chatroom_id']);
            $data = array(
                'chatroom_id' => $chat['id'],
                'sender_id' => $_SESSION['id'],
                'target_id' => $chat['user1_id'] == $_SESSION['id'] ? $chat['user2_id'] : $_SESSION['id'],
            );
            echo json_encode($data);
        } else {
            echo "ko";
        }

    }

    public function get_chat_messages()
    {
        if (isset($_GET['chat_id'])) {
            $messages = $this->ChatModel->get_chat_messages($_GET['chat_id']);
            echo json_encode($messages, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        }
    }

    public function new_chat()
    {
        if (isset($_POST['user1_id']) && is_numeric($_POST['user1_id']) && isset($_POST['user2_id']) && is_numeric($_POST['user2_id'])) {
            if ($this->ChatModel->chat_exists($_POST['user1_id'], $_POST['user2_id']) == false) {
                $this->ChatModel->new_chat($_POST['user1_id'], $_POST['user2_id']);
            }
            echo "ok";
        }
    }
}