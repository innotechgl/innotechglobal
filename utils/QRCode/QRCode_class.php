<?php

Class QRCode_class extends util_class
{
    public $dir = 'media/images/qrcodes/';
    public $remote_server = 'sweb/mobile';

    /*  */
    public function save_code($url)
    {
        global $engine;
        echo '<!-- kreiram -->';
        $name = md5($url);
        $remoteFile = 'http://chart.apis.google.com/chart?chs=100x100&cht=qr&chld=L|0&chl=' . $url;
        $localFile = $this->dir . $name . '.png';
        if (!file_exists($engine->path . $localFile)) {
            $ch = curl_init();
            $timeout = 0;
            curl_setopt($ch, CURLOPT_URL, $remoteFile);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
            $image = curl_exec($ch);
            curl_close($ch);
            $f = fopen($engine->path . $localFile, 'w');
            fwrite($f, $image);
            fclose($f);
        } else {
            //echo 'nema potrebe za kreiranjem.';
        }
    }

    public function show_code($url)
    {
        global $engine;
        // Show image
        include $engine->path . 'utils/QRCode/html/show.php';
    }

    /**
     * @name string $url
     */
    public function get_image_for_url($url)
    {
        global $engine;
        $name = md5($url);
        $filename = $this->dir . $name . '.png';
        $img = false;
        // Check if file exists
        if (file_exists($engine->path . $filename)) {
            $img['src'] = '/' . $filename;
            $img['size'] = getimagesize($engine->path . $filename);
        }
        return $img;
    }
}

?>