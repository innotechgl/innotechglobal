<?php
global $engine;
?>
<div class="comments">
    <a name="comment_<?php echo $this->get_count(); ?>"></a>
    <?php
    if ($engine->users->get_id() > 0) {
        ?>
        <a href="#comment_<?php echo $this->get_count(); ?>"
           onclick="comments_<?php echo $this->util_id; ?>.show_form(this,<?php echo $rel_id; ?>,0);">
            Ostavi komentar
        </a>
    <?php
    }
    ?>
    <?php
    foreach ($comments as $key_c => $val_c) {
        ?>
        <div class="comment">
            <div class="post_info">
                <?php
                echo $users[$val_c['creator']]['first_name'] . " " . $users[$val_c['creator']]['last_name'] . ' @ ' . date("d.m.Y. H:m", strtotime($val_c['created']));
                ?>
            </div>
            <!-- post_info -->
            <div class="text">
                <?php
                echo $val_c['comment'];
                ?>
            </div>
            <!-- text -->
        </div><!-- comment -->
    <?php
    }
    ?>
</div><!-- comments -->