<?php
require_once 'Session.php';

class Controller
{
    private $_vars = array();
    private $_params = 0;

    public function __construct()
    {
        session_start();

        // On génère un token
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(12));
        }
    }

    public function set($data)
    {
        $this->_vars = array_merge($this->_vars, $data);
    }

    public function render($filename)
    {
        extract($this->_vars);
        require ROOT . 'views/' . $filename . '.php';
    }

    public function loadView($filename, $data = null)
    {
        if (isset($data)) {
            $this->set($data);
        }
        $this->render($filename);
    }

    public function loadModel($name)
    {
        require_once ROOT . '/Models/' . strtolower($name) . '.php';
        $this->$name = new $name();
    }

    public function set_params($get_params)
    {
        $this->_params = $get_params;
    }
}