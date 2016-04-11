<?php
include_once 'includes/icons_and_buttons.php';
$icon = new icons_and_buttons();
$icon->icon = 'report';
$icon->tekst = 'Prijavi';
$icon->alt = 'Prijavi ovaj post';
$icon->script = "onClick=\"reports_" . $this->util_id . ".show_form(this,'" . $this->rel_id . "','" . $this->rel_page . "');\"";
echo $icon->kreiraj_ikonu();
?>