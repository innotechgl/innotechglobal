<div id="calendar">
</div><!-- calendar -->
<div style="clear:both;"></div>
<script type="text/javascript">
    var kal = new kalendar('<?php echo $this->insert_div; ?>', <?php echo $this->active_month; ?>, <?php echo $this->active_year; ?>, <?php echo $this->first_year; ?>, <?php echo $this->last_year; ?>, ['П', 'У', 'С', 'Ч', 'П', 'С', 'Н'], '<?php echo $this->get_url; ?>');
</script>