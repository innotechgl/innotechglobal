<div class="rating">
    <?php
    for ($i = 1; $i <= 5; $i++) {
        $mark = 'off';
        if ($i <= $rating) {
            $mark = 'on';
        }
        ?>
        <span class="<?php echo $mark; ?>"
              onclick="rate_it($(this).getParent(),<?php echo $this->get_rel_id() . ",'" . $this->get_rel_page() . "', " . $i; ?>);">&nbsp;</span>
    <?php
    }
    ?>
</div><!-- rating -->
<script type="text/javascript" src="/includes/js/utils/load_ajax.js"></script>
<script type="text/javascript" src="/utils/rating/js/rating.js"></script>