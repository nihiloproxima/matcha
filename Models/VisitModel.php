<?php

class VisitModel extends Model
{
    public function get_visit($key, $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM Visits WHERE $key = ?");
        $stmt->execute([$value]);

        return $stmt->fetch();
    }

    public function update_visit($sender_id, $user_id)
    {
        $stmt = $this->db->prepare("UPDATE `Visits` SET `creation_date` = CURRENT_TIMESTAMP WHERE `sender_id` = ? AND `user_id` = ?");
        $stmt->execute([$sender_id, $user_id]);
    }

    public function new_visit($sender_id, $user_id)
    {
        $stmt = $this->db->prepare("INSERT INTO `Visits`
          (`sender_id`, `user_id`) VALUES (?, ?)");
        return $stmt->execute([$sender_id, $user_id]);
    }

    public function get_users_who_visited($user_id)
    {
        $stmt = $this->db->prepare("SELECT u.*, p.path, v.* FROM Users u 
        RIGHT OUTER JOIN Pictures p ON p.id = u.profile_pic_id 
        INNER JOIN Visits v ON u.id = v.sender_id WHERE v.user_id = ?");
        $stmt->execute([$user_id]);
        return ($stmt->fetchAll());
    }

    public function get_last_visit($sender_id, $user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Visits WHERE `sender_id` = ? AND `user_id` = ? ORDER BY `creation_date` DESC LIMIT 1");
        $stmt->execute([$sender_id, $user_id]);

        return $stmt->fetch();
    }
}