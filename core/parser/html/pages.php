<div class="pagination">
    <div class="pages" id="pages">
        <div class="pages_cont" id="pages_cont">
            <?php
            // sef_requests_for_current_page
            $sq = $engine->sef->sef_request;
            if (!isset($engine->sef->sef_params['pnum'])) {
                $engine->sef->sef_params['pnum'] = 1;
            }

            for ($i = 1; $i <= $num_of_pages; $i++) {
                $class = '';
                if ($i == $engine->sef->sef_params['pnum']) {
                    $class = 'active';
                }
                $menu_id = '';
                if (isset($engine->sef->sef_params['menu_id'])) {
                    $menu_id = '?menu_id=' . $engine->sef->sef_params['menu_id'];
                }
                ?>
                <a href="<?php echo $engine->sef->constructLink(array($engine->sef->sef_params['page'], $engine->sef->sef_params['task'], $engine->sef->sef_params['id'], $i), 'index.php.html', $engine->type); ?><?php echo $menu_id; ?>"
                   class="<?php echo $class; ?>">
                    <?php
                    echo $i;
                    ?>
                </a>
            <?php
            }
            ?>
            <div style="clear: both;"></div>
        </div>
        <!-- pages_cont -->
    </div>
    <!-- pages -->
</div><!-- pagination -->