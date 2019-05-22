<?php

class ChatModel extends Model
{
    public function new_chat($user1, $user2)
    {
        $stmt = $this->db->prepare("INSERT INTO `Chat`(`user1_id`, `user2_id`) 
        VALUES (?, ?)");
        return $stmt->execute([$user1, $user2]);
    }

    public function chat_exists($user1, $user2)
    {
        $stmt = $this->db->prepare("SELECT * FROM Chat WHERE (`user1_id` = ? AND `user2_id` = ?) OR (`user1_id` = ? AND `user2_id` = ?)");
        $stmt->execute([$user1, $user2, $user2, $user1]);
        $count = count($stmt->fetchAll());
        return ($count > 0);
    }

    public function get_user_chats($id)
    {
        $stmt = $this->db->prepare("SELECT c.id as chat_id, u.username, u.id, u.last_connection, p.path FROM Chat c
        INNER JOIN Users u ON (u.id = c.user1_id OR u.id = c.user2_id) AND u.id != ?
        LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id
        WHERE c.user1_id = ? OR c.user2_id = ?");
        $stmt->execute([$id, $id, $id]);

        return ($stmt->fetchAll());
    }

    public function get_chat_infos($chatroomId)
    {
        $stmt = $this->db->prepare("SELECT * FROM Chat WHERE `id` = ?");
        $stmt->execute([$chatroomId]);

        return $stmt->fetch();
    }

    public function get_chat($key, $val)
    {
        $stmt = $this->db->prepare("SELECT c.*, u.username AS username1, b.username AS username2 FROM Chat c
        INNER JOIN Users u ON u.id = c.user1_id
        INNER JOIN Users b ON b.id = c.user2_id WHERE $key = ?");
        $stmt->execute([$val]);

        return ($stmt->fetch());
    }

    public function get_chat_messages($chatId)
    {
        $chat = $this->get_chat_infos($chatId);
        if (!empty($chat) && isset($_SESSION['id'])) {
            if ($chat['user1_id'] == $_SESSION['id'] || $chat['user2_id'] == $_SESSION['id']) {
                $stmt = $this->db->prepare("SELECT Chat_messages.*, u.username, i.path FROM Chat_messages INNER JOIN Users u ON Chat_messages.sender_id = u.id LEFT OUTER JOIN Pictures i ON i.id = u.profile_pic_id WHERE chatroom_id = ?");
                $stmt->execute([$chatId]);

                return ($stmt->fetchAll());
            }
        } else {
            return "Error";
         }
    }

    public function send_message($chatId, $senderId, $targetId, $content)
    {
        $stmt = $this->db->prepare("INSERT INTO `Chat_messages`
		(`chatroom_id`, `sender_id`, `target_id`, `content`)
		VALUES (?, ?, ?, ?)");
        $stmt->execute([$chatId, $senderId, $targetId, $content]);
    }

    public function update_message($messageID, $key, $val)
    {
        $stmt = $this->db->prepare("UPDATE `Chat_messages` SET $key = ? WHERE `id` = ?");
        return $stmt->execute([$val, $messageID]);
    }

    public function has_new_messages($user_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM Chat_messages WHERE `target_id` = ? AND `status` = 'unread'");
        $stmt->excute([$user_id]);

        return $stmt->fetch();
    }
}
