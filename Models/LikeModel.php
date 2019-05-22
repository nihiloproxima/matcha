<?php

class LikeModel extends Model
{
    public function get_user_likes($user_id)
    {
        $stmt = $this->db->prepare("SELECT u.id, u.username, l.*, p.path FROM Likes l 
        INNER JOIN Users u ON u.id = l.user_id
        LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id
        WHERE l.sender_id = ? ORDER BY creation_date DESC");
        if ($stmt->execute([$user_id])) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }

    public function like_user($sender_id, $user_id)
    {
        $stmt = $this->db->prepare("INSERT INTO `Likes` (`sender_id`, `user_id`, `creation_date`)
                                              VALUES (?, ?, CURRENT_TIMESTAMP)");
        return ($stmt->execute([$sender_id, $user_id]));
    }

    public function unlike_user($sender_id, $user_id)
    {
        $stmt = $this->db->prepare("DELETE FROM `Likes` WHERE `Likes`.`sender_id` = ? AND `Likes`.`user_id` = ?");
        return ($stmt->execute([$sender_id, $user_id]));
    }

    public function user_liked($sender_id, $user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Likes WHERE `sender_id` = ? AND `user_id` = ?");
        $stmt->execute([$sender_id, $user_id]);
        return (!empty($stmt->fetch()));
    }

    public function get_likes($key, $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM Likes WHERE $key = ?");
        $stmt->execute([$value]);
        return ($stmt->fetchAll());
    }

    public function get_users_who_liked($user_id)
    {
        $stmt = $this->db->prepare("SELECT u.*, p.path FROM Users u
        RIGHT OUTER JOIN Pictures p ON p.id = u.profile_pic_id
        INNER JOIN Likes l ON u.id = l.sender_id 
        WHERE l.user_id = ?");
        $stmt->execute([$user_id]);
        return ($stmt->fetchAll());
    }

    public function get_user_matches($userid)
    {
        $stmt = $this->db->prepare("SELECT a.*, b.*, u.id, p.path, u.username FROM `Likes` a, `Likes` b, `Users` u, `Pictures` p WHERE a.sender_id = ? AND a.user_id = b.sender_id AND b.user_id = ? AND u.id = b.sender_id AND p.user_id = b.sender_id");
        $stmt->execute([$userid, $userid]);
        return $stmt->fetchAll();
    }
}
