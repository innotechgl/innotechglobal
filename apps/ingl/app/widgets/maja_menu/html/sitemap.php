<div class="widget">
    <nav id="<?php echo $this->settings['customId']; ?>" class="menu <?php echo $this->settings['class_prefix']; ?>">
        <?php
        if (@trim($this->settings['title']) !== '') {
            ?>
            <h2><?php echo $this->settings['title']; ?></h2>
        <?php
        }
        ?>
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
                // print_r($fcats);
                echo "<a href='/" . implode("/", $als) . "/" . "' $selected id='menu_" . $val['id'] . "'>";
                if (trim($val['image']) !== '') {
                    ?>
                    <span class="photo">
                        <img src="/media/images/menu/<?php echo $val['parent_id']; ?>/<?php echo $val['image']; ?>"
                             align="absmiddle"/>
                    </span>
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
                        $engine->menu->get_path($val['id']);
                        $als = array();
                        $fcats = $engine->menu->get_founded_categories();
                        foreach ($fcats as $valAls) {
                            //print_r($valAls);
                            if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                                $als[] = $valAls['alias'];
                            }
                        }
                        echo "<a href='/" . implode("/", $als) . "/" . $val_c['alias'] . "' " . $item_selected . " >\r\n";
                        if (trim($val_c['image']) !== '') {
                            ?>
                            <span class="photo">
                                <img
                                    src="/media/images/menu/<?php echo $val_c['parent_id']; ?>/<?php echo $val_c['image']; ?>"
                                    align="absmiddle"/>
                            </span>
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
        <div style="clear:both;"></div>
    </nav>
</div><!-- .widget -->