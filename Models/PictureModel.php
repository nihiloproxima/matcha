<?php

class PictureModel extends Model
{
	public function get_picture($key, $value)
	{
        $stmt = $this->db->prepare("SELECT * FROM Pictures WHERE $key = ?");
        $stmt->execute([$value]);
        return ($stmt->fetch());
	}

    public function get_pictures($key, $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM Pictures WHERE $key = ? ORDER BY `creation_date` DESC LIMIT 5");
        $stmt->execute([$value]);
        return ($stmt->fetchAll());
    }

    public function get_pictures_count($userid)
    {
        $stmt = $this->db->prepare("SELECT count(*) AS pictures_count FROM Pictures WHERE `user_id` = ?");
        $stmt->execute([$userid]);
        return $stmt->fetch();
    }

    public function save_picture($userid, $path) {
        $stmt = $this->db->prepare("INSERT INTO `Pictures` (`user_id`, `path`) VALUES (?, ?)");
        return $stmt->execute([$userid, $path]);
    }

    public function has_photo($key, $value)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as 'Total' FROM Pictures WHERE $key = ?");
        $stmt->execute([$value]);
        $count = $stmt->fetch();
        if ($count['Total'] > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function save_user_pictures()
    {
        $error = "";
        $targetDir = 'assets/uploads/';
        foreach ($_FILES['files']['name'] as $key=>$val){
            // File upload path
            $fileName = basename($_FILES['files']['name'][$key]);
            $targetFilePath = $targetDir . $fileName;
            
            // Check whether file type is valid
			$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
            if (mime_content_type($_FILES['files']['tmp_name'][$key]) == 'image/jpeg' || mime_content_type($_FILES['files']['tmp_name'][$key]) == 'image/png') {
                $filename = $targetDir . uniqid() . "." . $fileType;

                // Upload file to server
                if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $filename)) {
                    // Image db insert sql
                    $this->save_picture($_SESSION['id'], $filename);
                } else{
                    $error = "Failed to upload img";
                }
            } else{
                $error = "Wrong type of file.";
            }
        }
        return $error;
	}
	
	public function delete_picture($key, $value)
	{
		$stmt = $this->db->prepare("DELETE FROM Pictures WHERE $key = ?");
		return $stmt->execute([$value]);
	}
}