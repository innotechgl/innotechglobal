<?php
include_once _ROOT_ . 'includes/icons_and_buttons.php';
$icons = array();
// New icon
$icon = new icons_and_buttons();
$icon->icon = 'new_item';
$icon->tekst = 'add widget';
$icon->script = 'onclick="widgets.showForm(\'new\');"';
$icons[] = $icon->kreiraj_ikonu();
// Sort icon
$icon = new icons_and_buttons();
$icon->icon = 'sort';
$icon->tekst = 'sort widgets';
$icon->script = 'onclick="widgets.showSort(\'sort\');"';
$icons[] = $icon->kreiraj_ikonu();/*
?>
<div class="widgets_toolbar" id="widget_toolbars_<?php echo $position; ?>">
    <div class="">Position: <?php echo $position; ?></div>
    <div class="widget_buttons">
        <?php
        // Show icons
        echo implode('&nbsp;',$icons);
        ?>
    </div>
</div><!-- widgets_toolbar -->
<?php 
*/
?>