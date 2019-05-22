<?php

class NotificationModel extends Model
{
    public function create_notification($user, $sender_id, $type)
    {
        $stmt = $this->db->prepare("INSERT INTO `Notifications`
        (`id`, `user_id`, `sender_id`, `object`, `content`, `creation_date`)
        VALUES (NULL, ?, ?, ?, ?, CURRENT_TIMESTAMP)");

        if ($user['notification_mails'] == 1) {
            $to = $user['email'];
            $subject = $type == "visit" ? "New visit on your profile" : "New like on your profile";
            $header = "From: no-reply@z4r6p2.le-101.fr";
            $content = 'You have a ' . $subject . '. Visit :
			http://localhost/index.php/profile/activity
                ---------------
			This mail was send automatically, please do not reply.';
            mail($to, $subject, $content, $header);
        }
        if ($type == "visit") {
            $stmt->execute([$user['id'], $sender_id, "New visit", $_SESSION['username'] . " just visited your profile."]);
        } else if ($type == "like") {
            $stmt->execute([$user['id'], $sender_id, "New like", $_SESSION['username'] . " liked you."]);
        } else if ($type == "unlike") {
            $stmt->execute([$user['id'], $sender_id, "Unlike", $_SESSION['username'] . " unliked you."]);
        } else if ($type == "match") {
            $stmt->execute([$user['id'], $sender_id, "New match", $_SESSION['username'] . " has matched with you."]);
        } else if ($type == "visit") {
            $stmt->execute([$user['id'], $sender_id, "New visit", $_SESSION['username'] . " visited your profile."]);
        }

        $id = $this->db->lastInsertId();
        $stmt = $this->db->prepare("SELECT * FROM Notifications WHERE `id` = $id");
        $stmt->execute();
        return $stmt->fetch();
    }

    public function get_user_notifications($key, $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM Notifications WHERE $key = ? ORDER BY `creation_date` DESC");
        $stmt->execute([$value]);
        return ($stmt->fetchAll());
    }

    public function get_notification($key, $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM Notifications WHERE $key = ?");
        $stmt->execute([$value]);
        return ($stmt->fetch());
    }

    public function delete_notification($notif_id)
    {
        $stmt = $this->db->prepare("DELETE FROM `Notifications` WHERE `id` = ?");
        return $stmt->execute([$notif_id]);
    }

    public function update_notification($id, $key, $value)
    {
        $stmt = $this->db->prepare("UPDATE Notifications SET $key = ? WHERE `id` = ? AND `user_id` = ?");
        $stmt->execute([$value, $id, $_SESSION['id']]);
    }
}