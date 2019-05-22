<?php

class TagModel extends Model
{
    public function get_tag($key, $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM Tags WHERE $key = ?");
        $stmt->execute([$value]);

        return ($stmt->fetch());
    }

    public function get_all_tags()
    {
        $stmt = $this->db->prepare("SELECT * FROM Tags");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function get_tag_entry($user_id, $tag_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Tag_entries WHERE `user_id` = ? AND `tag_id` = ?");
        $stmt->execute([$user_id, $tag_id]);

        return ($stmt->fetch());
    }

    public function get_tag_entries($key, $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM Tag_entries WHERE $key = ?");
        $stmt->execute([$value]);

        return ($stmt->fetchAll());
    }

    public function create_tag($name)
    {
        $stmt = $this->db->prepare("INSERT INTO `Tags` (`name`, `creation_date`)
        VALUES (?, CURRENT_TIMESTAMP)");

        return ($stmt->execute([$name]));
    }

    public function add_tag_entry($userid, $name)
    {
        $tag = $this->get_tag('name', $name);
        $tag_entry = $this->get_tag_entry($userid, $tag['id']);

        if (!$tag_entry) {
            $stmt = $this->db->prepare("INSERT INTO `Tag_entries` (`tag_id`, `user_id`, `creation_date`)
            VALUES (?, ?, CURRENT_TIMESTAMP)");

            return ($stmt->execute([$tag['id'], $userid]));
        }
    }

    public function flush_user_tags($key, $value)
    {
        $stmt = $this->db->prepare("DELETE FROM Tag_entries WHERE $key = ?");
        return $stmt->execute([$value]);
    }

    public function get_user_tags($userid)
    {
        $stmt = $this->db->prepare("SELECT * FROM Tags t INNER JOIN Tag_entries b ON b.tag_id = t.id WHERE b.user_id = ?");
        $stmt->execute([$userid]);
        return $stmt->fetchAll();
    }

    public function get_user_tagsName($userid)
    {
        $stmt = $this->db->prepare("SELECT `name`FROM Tags t INNER JOIN Tag_entries b ON b.tag_id = t.id WHERE b.user_id = ?");
        $stmt->execute([$userid]);
        return $stmt->fetchAll();
    }

    public function get_tagName($name)
    {
        $stmt = $this->db->prepare("SELECT `name` FROM Tags WHERE `name` = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public function get_tags_properly($tagsName)
    {
        $tags = array();
        for ($i = 0; $i < count($tagsName); $i++) {
            $tags[$i] = $this->get_tagName($tagsName[$i]);
        }
        return $tags;
    }
}
