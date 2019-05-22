<?php

class Session
{
    public function __construct($username)
    {
        session_start();
        $_SESSION['username'] = $username;
    }

    public function destroy()
    {
        session_start();
        unset($_SESSION['username']);
        session_destroy();
    }
}