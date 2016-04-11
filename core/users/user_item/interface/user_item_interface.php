<?php

interface user_item_interface
{
    public function delete();

    public function register($mail, $password, $password_again);

    public function login();

    public function logout();
}

?>