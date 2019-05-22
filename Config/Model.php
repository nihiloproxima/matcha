<?php

require_once 'Database.php';

class Model
{
    public $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->openConnection();
        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
            $stmt = $this->db->query("UPDATE Users SET `last_connection` = NOW() WHERE `id` = $id");
            $stmt->execute();
        }
    }

    public function loadModel($name)
    {
        require_once ROOT . 'Models/' . strtolower($name) . '.php';
        $this->$name = new $name();
    }

}