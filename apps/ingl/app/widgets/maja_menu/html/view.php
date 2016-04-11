<div class="widget">
    <?php
    if (@trim($this->settings['title']) !== '') {
        ?>
        <h3><?php echo $this->settings['title']; ?></h3>
    <?php
    }
    ?>
    <div class="widget-content">
        <nav id="<?php echo $this->settings['customId']; ?>"
             class="menu <?php echo $this->settings['class_prefix']; ?>">
            <ul>
                <?php
                $class = '';
                $num_of_menus = count($this->menu);
                $i = 0;
                foreach ($this->menu as $val) {
                    $selected = '';
                    $set_first = '';
                    if ($i == 0) {
                        $set_first = 'first';
                    }
                    if (@$engine->sef->sef_params['menu_id'] == $val['id']) {
                        $selected = 'class="selected"';
                    }
                    $i++;
                    $set_last = '';
                    if ($i == $num_of_menus) {
                        $set_last = 'no_line';
                    }
                    $class_selected = '';
                    if ($engine->menu->isInCurrentPath($val['id'])) {
                        $class_selected = 'class="active"';
                    }
                    echo "<li class='parent {$set_last} {$set_first} {$class_selected}'>\r\n";
                    $add = "?";
                    if (preg_match("/[?]/i", $val['link'])) {
                        $add = "&";
                    }
                    // getPath
                    $engine->menu->founded_categories = array();
                    $engine->menu->get_path($val['id']);
                    $als = array();
                    $fcats = $engine->menu->get_founded_categories();
                    foreach ($fcats as $valAls) {
                        //print_r($valAls);
                        if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                            $als[] = $valAls['alias'];
                        }
                    }
                    $link = "/" . implode("/", $als) . "/";
                    // Check with options
                    $menu_item = new menu_item;
                    $menu_item->set_options($val['options']);
                    $menu_item->options_to_array();
                    $options = $menu_item->get_options();
                    $target = '_self';
                    if (isset($options->type)) {
                        if ($options->type == 'external') {
                            $link = $val['link'];
                        }
                        $target = $options->target;
                    }
                    // print_r($fcats);
                    echo "<a href='" . $link . "' $selected id='menu_" . $val['id'] . "' target='" . $target . "'>";
                    if (trim($val['image']) !== '') {
                        ?>
                        <span class="photo"> <img
                                src="/media/images/menu/<?php echo $val['parent_id']; ?>/<?php echo $val['image']; ?>"
                                align="absmiddle"/> </span>
                    <?php
                    }
                    echo "<span>" . $val['name'] . "</span>" . "</a>\r\n";
                    if (isset($this->children[$val['id']])) {
                        $class_selected = '';
                        if ($engine->menu->isInCurrentPath($val['id'])) {
                            $class_selected = 'class="active"';
                        }
                        echo "<ul " . $class_selected . ">\r\n";
                        foreach ($this->children[$val['id']] as $key_c => $val_c) {
                            $item_selected = '';
                            if ($val_c['id'] == $engine->sef->sef_params['menu_id']) {
                                $item_selected = "class='selected'";
                            }
                            echo "<li class=\"" . $class . "\">";
                            // getPath
                            $engine->menu->founded_categories = array();
                            $engine->menu->get_path($val_c['id']);
                            $als = array();
                            $fcats = $engine->menu->get_founded_categories();
                            foreach ($fcats as $valAls) {
                                //print_r($valAls);
                                if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                                    $als[] = $valAls['alias'];
                                }
                            }
                            $link = "/" . implode("/", $als) . "/";
                            // Check with options
                            $menu_item = new menu_item;
                            $menu_item->set_options($val_c['options']);
                            $menu_item->options_to_array();
                            $options = $menu_item->get_options();
                            $target = '_self';
                            if (isset($options->type)) {
                                if ($options->type == 'external') {
                                    $link = $val['link'];
                                }
                                $target = $options->target;
                            }
                            echo "<a href='" . $link . "' " . $item_selected . " >\r\n";
                            if (trim($val_c['image']) !== '') {
                                ?>
                                <span class="photo"> <img
                                        src="/media/images/menu/<?php echo $val_c['parent_id']; ?>/<?php echo $val_c['image']; ?>"
                                        align="absmiddle"/> </span>
                            <?php
                            }
                            echo "<span>" . $val_c['name'] . "</span>"
                                . "</a></li>\r\n";
                            $i++;
                        }
                        echo "</ul>\r\n";
                    }
                    echo "</li>\r\n";
                }
                ?>
            </ul>
        </nav>
    </div>
    <!-- .widget-content -->
    <div style="clear:both;"></div>
</div>
<!-- .widget -->
<script type="text/javascript">
    $('#<?php echo $this->settings['customId']; ?> ul li ul').each(function () {
        $(this).css('display', 'none');
    });
    jQuery.each(
        $('#<?php echo $this->settings['customId']; ?> ul.active'),
        function (index, value) {
            $(value).css('display', '');
        });
</script>