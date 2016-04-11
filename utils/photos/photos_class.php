<?php

/**
 *
 * @author dajana
 * @package sCMS
 * @version 2.0
 */
class photos_class extends util_class
{

    public $folder = '';
    public $allowed = array('png', 'jpg', 'gif', 'zip', 'tar', 'swf');
    public $tmp_dir = 'temp/media/images';
    public $dir = 'media/images/';
    public $compression = 70;
    public $default_size = 500;
    public $newName = null;

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

    public function __construct($path = '')
    {
        parent::__construct();
        $this->tmp_dir = $this->engine->path . $this->tmp_dir . "/";
    }

    public function form_creator($num = 1, $expandable = false)
    {
        global $engine;
        include $engine->path . 'includes/photos/html/form.php';
    }

    private function move_uploaded_images_to_temp($data_name = 'photo')
    {
        global $engine;
        // create temp img file
        $sessid = session_id();
        $this->tmp_dir = $this->tmp_dir . $sessid;
        // Make new dir
        @mkdir($this->tmp_dir, 0777, true);
        switch ($data_name) {
            case "photo":
                // Check if we have array of files
                if (!is_array($_FILES[$data_name]['tmp_name'])) {
                    move_uploaded_file($_FILES[$data_name]['tmp_name'], $this->tmp_dir . "/" . $_FILES[$data_name]['name']);
                } else {
                    for ($i = 0; $i < count($_FILES[$data_name]['tmp_name']); $i++) {
                        if (!move_uploaded_file($_FILES[$data_name]['tmp_name'][$i], $this->tmp_dir . "/" . $_FILES[$data_name]['name'][$i])) {
                            $engine->log->add_error_log('util_photos', 'OVAJ! >>>>>> ' . $_FILES[$data_name]['tmp_name'][$i]);
                            $engine->log->add_error_log('util_photos', 'OVDE! >>>>>> ' . $this->tmp_dir . "/" . $_FILES[$data_name]['name'][$i]);
                        } else {
                            $engine->log->add_success_log('util_photos', 'SUCCESS');
                        };
                    }
                }
                break;
            default:
                move_uploaded_file($_FILES[$data_name]['tmp_name'], $this->tmp_dir . "/" . $_FILES[$data_name]['name']);
                break;
        }
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

    public function createResizedImg($size = array(), $prefix = array(), $data_name = 'photo')
    {
        // Move all files to Temp dir
        $this->move_uploaded_images_to_temp($data_name);
        // Check for zipped files
        if (class_exists('ZipArchive')) {
            $this->unzip();
        }
        $files = $this->load_dir($this->tmp_dir);
        $arr_imgs = array();
        // Go through files
        foreach ($files as $key => $val) {
            #$key_f = array_search(mime_content_type($this->tmp_dir."/".$val), $this->mime_types);
            $ext_arr = explode(".", $val);
            $ext = $ext_arr[count($ext_arr) - 1];
            if (isset($this->mime_types[strtolower($ext)]) && $ext !== 'swf') {
                $arr_imgs[] = $val;
                chmod($this->tmp_dir . "/" . $val, 0777);
                if (count($size) > 0) {
                    // New name assingment
                    $name = $val;
                    if ($this->newName !== null) {
                        $name = $this->newName . "." . $ext;
                    }
                    foreach ($size as $key_size => $val_size) {
                        $prefix_val = '';
                        $prefix_val = $prefix[$key_size];
                        $this->resize_photo($val, $this->tmp_dir . "/", $val_size, $prefix_val, $this->dir, $name, $this->compression);
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
            @unlink($this->tmp_dir . "/" . $val);
        }
        //@rmdir($this->tmp_dir);
        return $arr_imgs;
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
                @unlink($this->tmp_dir . "/" . $val);
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
        if (file_exists($dir . $naziv)) {
            $velicina = getimagesize($dir . $naziv);
        } else {
            return false;
        }
        if (!is_dir($dest_dir)){
            mkdir($dest_dir, 0775, true);
        }
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
                $foto = imagecreatefromjpeg($dir . $naziv);
                $thumb = imagecreatetruecolor(ceil($sirina), ceil($visina));
                imagecopyresampled($thumb, $foto, 0, 0, 0, 0, ceil($sirina), ceil($visina), $velicina[0], $velicina[1]);
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

        @chmod($dest_dir . "{$prefix}{$nov_naziv}", 0755);

        if (isset($thumb)) {
            @imagedestroy($thumb);
        }
        if (isset($foto)) {
            @imagedestroy($foto);
        }
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

    /**
     *
     * @param string $path
     * @return multitype:array
     */
    private function load_dir($path)
    {
        $files = array();
        $dirFiles = scandir($path);
        foreach ($dirFiles as $file) {
            if ($file != "." && $file != "..") {
                $files [] = $file;
            }
        }
        return $files;
    }

    /**
     *
     * @param string $text
     * @param string $prefix_set
     * @return boolean
     */
    public function get_img_from_text($text, $prefix_set = '')
    {
        global $engine;
        include_once $engine->path . 'includes/scripts/simple_html_dom.php';
        $img = false;
        $engine->load_page('article_photos');
        $html = str_get_html($text);
        if ($html) {
            $imgs = $html->find('img');
            if (isset($imgs[0])) {
                if (count(@$imgs[0]->src) > 0) {
                    $parts = explode("/", $imgs[0]->src);
                    $last_part = count($parts) - 1;
                    $prefix = explode("_", $parts[$last_part]);
                    // Search for _ prefix
                    if (preg_match("/_/", $parts[$last_part])) {
                        # Explode string to get prefix
                        $prefix = explode("_", $parts[$last_part]);
                        # Check if there is prefix in articles
                        if (in_array($prefix[0] . "_", $engine->article_photos->prefixes)) {
                            # replace
                            $prefix[0] = $prefix_set;
                        } else {
                            # add new prefix
                            $prefix[0] = $prefix_set . $prefix[0];
                        }
                        $parts[$last_part] = implode("_", $prefix);
                        $merged = implode("/", $parts);
                        $img['tag'] = "<img " . implode("/", $parts) . " border=0 />";
                        $img['src'] = preg_replace('/(src=)?(")?(\')?/i', '', $merged);
                    } else {
                        $parts[$last_part] = $prefix_set . $parts[$last_part];
                        $merged = implode("/", $parts);
                        $img['tag'] = "<img " . implode("/", $parts) . " border=0 />";
                        $img['src'] = preg_replace('/(src=)?(")?(\')?/i', '', $merged);
                    }
                } else {
                    $img = false;
                }
            } else {
                $img = false;
            }
        }
        return $img;
        /* foreach ($imgs as $k => $val) {
          $src_e = explode("/", $val->src);
          if ($src_e[0] == '') {
          unset($src_e[0]);
          }
          if (!file_exists(implode("/", $src_e))) {
          $imgs[$k]->class = 'hideMe';
          }
          }
          echo $html; */
    }

    /**
     *
     * @param string $text
     * @param string $prefix_set
     * @return boolean
     */
    public function _get_img_from_text($text, $prefix_set = '')
    {
        global $engine;
        include_once $enigne->path . 'includes/scripts/simple_html_dom.php';
        $engine->load_page('article_photos');
        $img = array();
        $pattern = "/src=[\"']?([^\"']?.*(png|jpg|gif))[\"']?/i";
        /* @var $regs array */
        if (preg_match($pattern, $text, $regs)) {
            $result = $regs[0];
            $parts = explode("/", $result);
            $last_part = count($parts) - 1;
            // Search for _ prefix
            if (preg_match("/_/", $parts[$last_part])) {
                # Explode string to get prefix
                $prefix = explode("_", $parts[$last_part]);
                # Check if there is prefix in articles
                if (in_array($prefix[0] . "_", $engine->article_photos->prefixes)) {
                    # replace
                    $prefix[0] = $prefix_set;
                } else {
                    # add new prefix
                    $prefix[0] = $prefix_set . $prefix[0];
                }
                $parts[$last_part] = implode("_", $prefix);
                $merged = implode("/", $parts);
                $img['tag'] = "<img " . implode("/", $parts) . " border=0 />";
                $img['src'] = preg_replace('/(src=)?(")?(\')?/i', '', $merged);
            } else {
                $parts[$last_part] = $prefix_set . $parts[$last_part];
                $merged = implode("/", $parts);
                $img['tag'] = "<img " . implode("/", $parts) . " border=0 />";
                $img['src'] = preg_replace('/(src=)?(")?(\')?/i', '', $merged);
            }
        } else {
            $img = false;
        }
        return $img;
    }

    /**
     *
     * @param string $text
     * @return string
     */
    public function remove_all_imgs_from_text($text)
    {
        $clean_text = preg_replace('/<img[^>]+>/ism', "", $text);
        return $clean_text;
    }

    /**
     * Save image from URL
     * @param string $src Source URL of file
     * @param string $destination Destination
     * @return array
     */
    public function saveImgFromUrl($src, $destination)
    {
        $result = array("result" => "", "imgPath" => "");
        // Url_fopen 
        if (ini_get('allow_url_fopen')) {
            $url = $src;
            $img = $destination;
            $res = file_put_contents($img, file_get_contents($url));
            if ($res) {
                $result['result'] = true;
                $result['imgPath'] = $destination;
                return $result;
            } else {
                $result['result'] = false;
                $result['imgPath'] = null;
                return $result;
            }
        } elseif (in_array('curl', get_loaded_extensions())) {
            $ch = curl_init($src);
            $fp = fopen($destination, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            $result['result'] = true;
            $result['imgPath'] = $destination;
            return $result;
        } else {
            $result['result'] = false;
            $result['imgPath'] = null;
            $result['error'] = 'There is no way to save img.';
            return $result;
        }
    }
}

?>