<div class="widget novosti <?php echo $this->settings['class_prefix']; ?>" id="widget_<?php echo $this->id; ?>">
    <h3>Najave događaja</h3>

    <div class="widget-content">
        <ul>
            <?php
            foreach ($this->articles as $key => $val) {
                $article = new article;
                $article = $val['article'];
                $thumbSize = '';
                if (@$this->settings['thumbSize'] > 0) {
                    $thumbSize = 'width="' . $this->settings['thumbSize'] . '"';
                }
                // getPath
                $engine->article_categories->founded_categories = array();
                $engine->article_categories->get_path($article->get_categorie_id());
                $als = array();
                $fcats = $engine->article_categories->get_founded_categories();
                foreach ($fcats as $valAls) {
                    if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                        $als[] = $valAls['alias'];
                    }
                }
                $link = implode("/", $als) . "/" . $article->get_alias() . '/';

                ?>
                <li><a href="<?php echo $link; ?>" class="title"><?php echo $article->get_title(); ?></a> <a href="#"
                                                                                                             class="date"><?php echo $article->get_date(); ?></a>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <!-- .widget-content -->
</div><!-- .novosti -->