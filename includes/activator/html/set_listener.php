<!-- load main_listener_class -->
<script type="text/javascript"
        src="<?php echo $engine->settings->general->server . "includes/activator/js/activator.js" ?>"></script>
<!-- add listener -->
<script type="text/javascript">
    var activator_listener = new activator_class('<?php echo $this->id_to_listen; ?>', '<?php echo $engine->sef->constructLink(array($this->ajax_link,"activate"),'index.php','ajax'); ?>');
</script>