<?php

class ReportModel extends Model
{
    public function get_reports($key = NULL, $value = NULL)
    {
        if ($key && $value) {
            $stmt = $this->db->prepare("SELECT * FROM Reports WHERE ? = ?");
            $stmt->execute([$key, $value]);
            return $stmt->fetch();
        }
        $stmt = $this->db->prepare("SELECT r.*, u.id as userid, s.username as sendername,  u.username, p.path FROM Reports r 
        INNER JOIN Users s ON s.id = r.sender_id INNER JOIN Users u ON u.id = r.reported_id LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function new_report($sender_id, $reported_id)
    {
        $stmt = $this->db->prepare("INSERT INTO `Reports`
        (`sender_id`, `reported_id`) 
        VALUES (?, ?)");
        return $stmt->execute([$sender_id, $reported_id]);
    }

    public function delete_report($key, $value)
    {
        $stmt = $this->db->prepare("DELETE FROM Reports WHERE $key = ?");
        $stmt->execute([$value]);
    }

    public function is_reported($user_id, $target_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM Reports WHERE `sender_id` = ? AND `reported_id` = ?");
        $stmt->execute([$user_id, $target_id]);
        $count = $stmt->fetch();
       return ($count['total'] > 0);
    }

    public function report_exists($userId, $targetId)
    {
        $stmt = $this->db->prepare("SELECT Reports.id FROM Reports WHERE `sender_id` = ? AND `reported_id` = ? LIMIT 1;");
        $stmt->execute([$userId, $targetId]);
        return $stmt->fetch();
    }
}