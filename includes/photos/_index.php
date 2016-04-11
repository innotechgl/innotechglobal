<?php

class photos
{

    public $folder = '';
    public $allowed = array('png', 'jpg', 'gif', 'zip', 'tar', 'swf');
    public $tmp_dir = 'temp/media/images';
    public $dir = 'media/images/';
    public $compression = 70;
    public $default_size = 500;
    public $mime_types = array(
        // images
        '' => '',
        'png' => 'image/png',
        //'jpe' => 'image/jpeg',
        //'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        'swf' => 'application/x-shockwave-flash'
    );
    private $engine;

    public function __construct($path = '')
    {
        global $engine;
        $this->tmp_dir = $engine->path . $this->tmp_dir . "/";
        $log[] = 'temp dir: ' . $this->tmp_dir;
    }

    public function form_creator($num = 1, $expandable = false)
    {
        global $engine;
        include $engine->path . 'includes/photos/html/form.php';
    }

    public function createResizedImg($size = array(), $prefix = array())
    {
        // Move all files to Temp dir
        $this->move_uploaded_images_to_temp();
        // Check for zipped files
        // $this->check_and_unzip();
        // Proceed files
        if (class_exists('ZipArchive')) {
            $this->unzip();
        }
        $files = $this->load_dir($this->tmp_dir);
        $arr_imgs = array();
        foreach ($files as $key => $val) {
            #$key_f = array_search(mime_content_type($this->tmp_dir."/".$val), $this->mime_types);
            $ext_arr = explode(".", $val);
            $ext = $ext_arr[count($ext_arr) - 1];
            if (isset($this->mime_types[$ext]) && $ext !== 'swf') {
                $arr_imgs[] = $val;
                chmod($this->tmp_dir . "/" . $val, 0777);
                if (count($size) > 0) {
                    foreach ($size as $key_size => $val_size) {
                        $prefix_val = '';
                        $prefix_val = $prefix[$key_size];
                        $this->resize_photo($val, $this->tmp_dir . "/", $val_size, $prefix_val, $this->dir, $val, $this->compression);
                    }
                    // Remove file
                    #unlink($this->tmp_dir."".$val);
                } else {
                    $this->resize_photo($val, $this->tmp_dir . "/", $this->default_size, '', $this->dir, $val, $this->compression);
                }
            } else {
                // Check for swf
                $ext_arr = explode(".", $val);
                $ext = $ext_arr[count($ext_arr) - 1];
                # Check extension
                if ($ext == 'swf') {
                    # Check if allowed
                    if (in_array($ext, $this->allowed)) {
                        // Copy file
                        if (copy($this->tmp_dir . "/" . $val, $this->dir . $val)) {
                            $arr_imgs[] = $val;
                        }
                    }
                }
            }
        }
        foreach ($files as $key => $val) {
            unlink($this->tmp_dir . "/" . $val);
        }
        rmdir($this->tmp_dir);
        return $arr_imgs;
    }

    private function move_uploaded_images_to_temp()
    {
        // create temp img file
        $sessid = session_id();
        $this->tmp_dir = $this->tmp_dir . $sessid;
        @mkdir($this->tmp_dir, 0777, true);
        for ($i = 0; $i < count($_FILES['photo']); $i++) {
            @move_uploaded_file($_FILES['photo']['tmp_name'][$i], $this->tmp_dir . "/" . $_FILES['photo']['name'][$i]);
        }
    }

    public function unzip()
    {
        // Load ZIP class
        $zip = new ZipArchive();
        // Get all files
        $files = $this->load_dir($this->tmp_dir);
        foreach ($files as $key => $val) {
            // checkout
            $ext_s = explode(".", $val);
            if ($ext_s[count($ext_s) - 1] == "zip") {
                $zip->open($this->tmp_dir . "/" . $val);
                $zip->extractTo($this->tmp_dir);
                unlink($this->tmp_dir . "/" . $val);
            }
        }
    }

    /**
     * Kreiranje srazmerne fotografije
     *
     * @param str $naziv
     * @param str $tip
     * @param str $dir
     * @param str $velicina
     * @param str $prefix
     * @param bool $new
     * @param int $compress
     *
     */
    public function resize_photo($naziv, $dir = '', $velicina_nova = '', $prefix = null, $dest_dir = null, $nov_naziv = null, $compression = 90)
    {
        $velicina = getimagesize($dir . $naziv);
        @mkdir($dest_dir, 0755, true);
        //
        $tip = array_search($velicina['mime'], $this->mime_types);
        // Proveravamo koja je veca stranica
        if ($velicina[0] > $velicina[1]) {
            if ($velicina[0] > $velicina_nova) {
                $sirina = $velicina_nova;
                # Racunamo nove parametre
                $ratio = $velicina[0] / $sirina;
                $visina = $velicina[1] / $ratio;
            } else {
                $sirina = $velicina[0];
                $visina = $velicina[1];
            }
        } else {
            if ($velicina[1] > $velicina_nova) {
                $visina = $velicina_nova;
                # Racunamo nove parametre
                $ratio = $velicina[1] / $visina;
                $sirina = $velicina[0] / $ratio;
            } else {
                $sirina = $velicina[0];
                $visina = $velicina[1];
            }
        }
        // Proveravamo da li postoji dest_dir
        if ($dest_dir == null) {
            $dest_dir = $dir;
        }
        if ($nov_naziv == null) {
            $nov_naziv = $naziv;
        }
        # Kreiramo srazmernu fotografiju na osnovu tipa
        switch (strtolower($tip)) {
            case "jpg":
            case "jpeg":
                $foto = imagecreatefromjpeg("$dir$naziv");
                $thumb = imagecreatetruecolor($sirina, $visina);
                imagecopyresampled($thumb, $foto, 0, 0, 0, 0, $sirina, $visina, $velicina[0], $velicina[1]);
                @imagejpeg($thumb, $dest_dir . "{$prefix}{$nov_naziv}", $compression);
                break;
            case "gif":
                $foto = imagecreatefromgif("$dir$naziv");
                $thumb = imagecreatetruecolor($sirina, $visina);
                imagecopyresampled($thumb, $foto, 0, 0, 0, 0, $sirina, $visina, $velicina[0], $velicina[1]);
                @imagegif($thumb, $dest_dir . "{$prefix}{$nov_naziv}");
                break;
            case "png":
                $foto = imagecreatefrompng("$dir$naziv");
                $thumb = imagecreatetruecolor($sirina, $visina);
                imagealphablending($thumb, false); // setting alpha blending on
                // Create a new transparent color for image
                $color = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                // Completely fill the background of the new image with allocated color.
                imagefill($thumb, 0, 0, $color);
                imagesavealpha($thumb, true); // save alphablending setting (important)
                imagecopyresampled($thumb, $foto, 0, 0, 0, 0, $sirina, $visina, $velicina[0], $velicina[1]);
                imagepng($thumb, $dest_dir . "{$prefix}{$nov_naziv}");
                break;
            default:
                //echo "Tip fotografije nije poznat";
                break;
        }
        chmod($dest_dir . "{$prefix}{$nov_naziv}", 0555);
        imagedestroy($thumb);
        imagedestroy($foto);
        return true;
    }

    public function get_photos_dir($limit = 0, $prefixes = array())
    {
        $files = $this->load_dir($this->folder);
        $array = array();
        $i = 0;
        foreach ($files as $file) {
            $i++;
            $prefix_s = array();
            $prefix_s = explode("_", $file);
            $prefix = $prefix_s[0];
            if (count($prefixes) > 0 && in_array($prefix, $prefixes)) {
                $array[$prefix][] = $file;
            } else {
                $prefix = 'without_prefix';
                $array[$prefix][] = $file;
            }
            // exit function if limit reached
            if ($limit > 0 && $i == $limit) {
                exit();
            }
        }
        return $array;
    }

    private function check_and_unzip()
    {
        $archive = new archive('');
        $files = $this->load_dir($this->tmp_dir);
        $ext = '';
        foreach ($files as $key => $val) {
            $ext_s = explode(".", $val);
            if (!in_array($ext_s[count($ext_s) - 1], $this->allowed)) {
                // Remove file
                unlink($this->tmp_dir . $val);
            } else {
                // Check if it's zipped
                if ($ext_s[count($ext_s) - 1] == 'zip' || $ext_s[count($ext_s) - 1] == 'tar') {
                    $zipovano = new tar_file($val);
                    $zipovano->set_options(array('inmemory' => 0, 'basedir' => $this->tmp_dir));
                    $zipovano->extract_files();
                    $log[] = 'file: ' . $val;
                }
            }
        }
    }

    private function load_dir($path)
    {
        $files = array();
        $dh = opendir($path);
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {
                $files [] = $file;
            }
        }
        closedir($dh);
        return $files;
    }
}

?>