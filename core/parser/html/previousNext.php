<div class="pagination">
    <?php
    if ($engine->dbase->page_no > 1) {
        //$link = $engine->sef->constructLink(array($engine->sef->sef_params['page'],$engine->sef->sef_params['task'],$engine->sef->sef_params['id'],($engine->dbase->page_no - 1)),'index.php.html',$engine->type);
        ?><?php
        //echo "?menu_id=" . (int) $engine->sef->sef_params['menu_id'];
        $link = $engine->sef->changeParamInCurrentLink('pnum', ($engine->dbase->page_no - 1));
        ?>
        <a href="<?php echo $link; ?>" class="previousPage">
            &lt;&lt;
        </a>
    <?php
    }
    ?>
    <?php
    if ($engine->dbase->page_no < $engine->dbase->num_of_pages) {
        //$link =  $engine->sef->constructLink(array($engine->sef->sef_params['page'],$engine->sef->sef_params['task'],$engine->sef->sef_params['id'],($engine->dbase->page_no + 1)),'index.php.html',$engine->type);
        ?><?php
        //echo "?menu_id=" . (int) $engine->sef->sef_params['menu_id'];
        $link = $engine->sef->changeParamInCurrentLink('pnum', ($engine->dbase->page_no + 1));
        ?>
        <a href="<?php echo $link; ?>" class="nextPage">
            &gt;&gt;
        </a>
    <?php
    }
    ?>
</div><!-- pagination -->