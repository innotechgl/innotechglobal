<?php

/**
 * @name Installer
 * @author Dajana Nestorovic
 * @version 1.0
 * @package sCMS ver 2.0
 */
class installer
{
    private $engine;
    private $install_dir = '../temp/installer';

    public function  __construct()
    {
        global $engine;
        $engine = $engine;
        mkdir($this->install_dir, 0750, true);
    }

    public function install()
    {
        // Get requested files
        $requested = array("install_file");
        $vars = $engine->security->get_vals($requested);
        // Move all uploaded files to tmp_dir
        foreach ($vars as $key => $val) {
            mkdir($this->install_dir . "/" . $key);
            if (move_uploaded_file($val['tmp_name'], $this->install_dir . "/" . $key)) {
                // Read config file
            }
        }
    }

    public function readSetupProgram($path, $programm)
    {
    }
}

?>