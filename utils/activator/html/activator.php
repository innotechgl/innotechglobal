<?php
include_once $engine->path . 'includes/icons_and_buttons.php';
$checked = '';
if ($this->active == 1) {
    $checked = 'checked=checked';
}
?>
<input type="checkbox" name="active[]" value="<?php echo $this->id; ?>" class="activator" <?php echo $checked; ?> />
<!-- $engine->sef->constructLink(array($this->ajax_link,"activate",$this->id,$this->active),'index.php','ajax'); -->