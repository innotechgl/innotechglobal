<?php

/**
 * Icons & buttons
 *
 */
class icons_and_buttons
{

    public $path;
    public $_icons;

    public $icon = '';
    public $tekst = '';
    public $link = '';
    public $align = 'absmiddle';
    public $confirm = false;
    public $script = '';
    public $id = '';
    public $class = '';
    public $alt = '';

    /**
     * Konstruisemo klasu
     *
     * @param string $path
     */
    public function __construct($path = null)
    {
        global $engine;
        //$staza = $engine->path.'';
        if ($path == null) {
            $this->_path = $engine->settings->general->server . "media/images/icons";
        } else {
            $this->_path = $path;
        }
        $this->_icons = array(
            'admin_lista' => "tables.gif",
            'save' => "action_save.gif",
            'delete' => "page_cross.gif",
            'cancel' => "action_back.gif",
            'new_item' => "page_new.gif",
            'refresh' => "action_refresh_blue.gif",
            'edit' => "page_edit.gif",
            'sort' => "sort.gif",
            'lock' => "icon_padlock.gif",
            'check' => "icon_accept.gif",
            'uncheck' => "action_stop.gif",
            'settings' => "page_settings.gif",
            'upload' => "icon_package_open.gif",
            'dole' => "move_down.gif",
            'wizard' => "icon_wand.gif",
            'calendar' => "calendar.gif",
            'attach' => "page_attachment.gif",
            'left' => "arrow_left.gif",
            'right' => "arrow_right.gif",
            'alert' => "icon_alert.gif",
            'pass' => "icon_key.gif",
            'active' => "icon_accept.gif",
            'pasive' => "action_stop.gif",
            'secure_pages' => "list_security.gif",
            'copy' => "copy.gif",
            'note_new' => "note_new.gif",
            'note_delete' => "note_delete.gif",
            'join' => "icon_link.gif",
            'leave' => "icon_alert.gif",
            'report' => "flag_red.gif",
            'fancy_lock' => "lock_16.png",
            'link' => "page_link.gif"
        );
    }

    /**
     * Creating icons
     *
     * @param array $icons
     * @example $icons = array("icon:edit,link:http://www.mayor.org,text:Edit,confirm:false,scipt:onclick=checkAll()","icon:delete,link:http://...,confirm:false")
     *
     */
    public function icons($icons = array())
    {
        foreach ($icons as $icon) {
            $icon = preg_replace("/%,%/i", "%%%", $icon);
            $icon_item = explode(",", $icon);
            $icon_img = array();
            $icon_link = array();
            $icon_text = array();
            $icon_confirm = array();
            $icon_script = array();
            if (isset($icon_item[0])) {
                $icon_img = explode(":", preg_replace("/%%%/i", ",", $icon_item[0]));
            }
            if (isset($icon_item[1])) {
                $icon_link = explode(":", preg_replace("/%%%/i", ",", $icon_item[1]));
            }
            if (isset($icon_item[2])) {
                $icon_text = explode(":", preg_replace("/%%%/i", ",", $icon_item[2]));
            }
            if (isset($icon_item[3])) {
                $icon_confirm = explode(":", preg_replace("/%%%/i", ",", $icon_item[3]));
            }
            if (isset($icon_item[4])) {
                $icon_script = explode(":", preg_replace("/%%%/i", ",", $icon_item[4]));
            }
            $confirm = '';
            if (isset($icon_confirm[1])) {
                if ($icon_confirm[1] !== "" && $icon_confirm[1] !== "null") {
                    if ($icon_confirm[1] == "true") {
                        $confirm = "rel='confirm'";
                    }
                }
            }
            $linkovanje = "";
            if (count($icon_link) > 0) {
                if ($icon_link[1] !== "" && $icon_link[1] !== "null") {
                    $linkovanje = "href='{$icon_link[1]}'";
                }
            }
            $skripta = '';
            if (isset($icon_script[1])) {
                $skripta = $icon_script[1];
            }
            $text = '';
            if (isset($icon_text[1])) {
                $text = $icon_text[1];
            }
            echo "<a {$linkovanje} style='margin-right:5px;' {$confirm} {$skripta}><img src='" . $this->_path . "/" . $this->_icons[$icon_img[1]] . "' align='absmiddle' style='margin-right:5px;' border=0 />{$text}</a>";
        }
    }

    public function samo_img($icon = '', $alt = '', $align = 'absmiddle', $border = 0)
    {
        return "<img src='" . $this->_path . "/" . $this->_icons[$icon] . "' align=\"{$align}\" border=\"{$border}\" alt=\"{$alt}\" title=\"{$alt}\" />";
    }

    public function kreiraj_ikonu()
    {
        $class = '';
        $script = '';
        $id = '';
        $tekst = '';
        $confirm = '';
        $link = '';
        $icon = "<a %class% %script% id %confirm% %link% %title% ><img %src% %align% border=0 />tekst</a>";
        // Proveravamo klasu
        if (trim($this->class) !== '') {
            $class = "class='{$this->class}'";
        }
        // Proveravamo skriptu
        if (trim($this->script) !== '') {
            $script = $this->script;
        }
        // proveravamo id
        if (trim($this->link) !== '') {
            $link = "href='" . $this->link . "'";
        }
        if (trim($this->id) !== '') {
            $id = "id='{$this->id}'";
        }
        // Proveravamo tekst
        if (trim($this->tekst) !== '') {
            $tekst = $this->tekst;
        }
        if ($this->confirm == true) {
            $confirm = "rel='confirm'";
        }
        $align = "align=\"{$this->align}\"";
        // postavljamo sve na svoje mesto
        $icon = preg_replace('/%class%/', $class, $icon);
        $icon = preg_replace('/%script%/', $script, $icon);
        $icon = preg_replace('/id/i', $id, $icon);
        $icon = preg_replace('/tekst/', $tekst, $icon);
        $icon = preg_replace('/%confirm%/', $confirm, $icon);
        $icon = preg_replace('/%align%/', $align, $icon);
        $icon = preg_replace('/%link%/', $link, $icon);
        $icon = preg_replace('/%title%/', 'title="' . $this->alt . '"', $icon);
        $icon = preg_replace('/%src%/', 'src="' . $this->_path . '/' . $this->_icons[$this->icon] . '"', $icon);
        return $icon;
    }
}

?>