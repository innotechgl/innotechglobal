<div id="upr_photo_form">
    <?php
    if ($expandable == true) {
        ?>
        <button type="button" id="add">Add</button><br/>
    <?php
    }
    ?>
    <?php $url = $_SERVER["REQUEST_URI"];
    for ($i = 1; $i <= $num; $i++) {
        ?>
        <?php if (strpos("gallery", $url)) { ?>
            <input type="text" name="title[]" size="10"/>
        <?php } ?>
        <input type="file" name="photo[]" size="40"/>
    <?php
    }
    ?>
</div>
<?php
if ($expandable == true) {
    ?>
    <script type="text/javascript" src="<?php echo '/includes/photos/js/photo_form.js' ?>"></script>
<?php
}
?>
