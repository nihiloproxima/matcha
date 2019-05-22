<?php

class BlacklistModel extends Model
{

    public function new_blacklist_entry($user_id, $blacklisted_id)
    {
        $stmt = $this->db->prepare("INSERT INTO `Blacklist_entries`
        (`user_id`, `blacklisted_id`) 
        VALUES (?, ?)");
        return $stmt->execute([$user_id, $blacklisted_id]);
    }

    public function get_blacklisted_users($user_id)
    {
        $stmt = $this->db->prepare("SELECT b.*, u.username, u.id, p.path FROM `Blacklist_entries` b
        INNER JOIN Users u ON u.id = b.blacklisted_id
        LEFT OUTER JOIN Pictures p ON p.user_id = b.blacklisted_id AND u.profile_pic_id = p.id 
        WHERE b.user_id = ?");
        $stmt->execute([$user_id]);
        return ($stmt->fetchAll());
    }

    public function is_blacklisted($blacklisted_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM `Blacklist_entries` WHERE `blacklisted_id` = ?");
        $stmt->execute([$blacklisted_id]);

        return $stmt->fetchAll();
    }

    public function is_blacklisted_by($user_id, $target_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM Blacklist_entries WHERE `user_id` = ? AND `blacklisted_id` = ?");
        $stmt->execute([$user_id, $target_id]);
        $count = $stmt->fetch();
       return ($count['count'] > 0);
    }

    public function unblacklist_user($user_id, $blacklisted_id)
    {
        $stmt = $this->db->prepare("DELETE FROM `Blacklist_entries` WHERE `user_id` = ? AND `blacklisted_id` = ?");
        return $stmt->execute([$user_id, $blacklisted_id]);
	}
	
	public function get_blacklist_entries()
	{
		$stmt = $this->db->prepare("SELECT b.creation_date, u.id, u.username AS sendername, t.id, t.username  AS targetname  FROM Blacklist_entries b INNER JOIN Users u ON u.id = b.user_id INNER JOIN Users t ON t.id = b.blacklisted_id ORDER BY b.creation_date");
		$stmt->execute();
		return $stmt->fetchAll();
	}
}