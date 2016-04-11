<div class="widget <?php echo $this->settings['class_prefix']; ?>" id="widget_<?php echo $this->id; ?>">
    <div class="widget-content">
        <div class="row-fluid">
            <div class="col col50">
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
                        //print_r($valAls);
                        if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                            $als[] = $valAls['alias'];
                        }
                    }
                    // $link = "/articles/view/" . $article->get_categorie_id() . "/" . $article->get_id() . "/" . $engine->sef->filename_sef($article->get_title()) . ".html?menu_id=" . (int) $engine->sef->sef_params['menu_id'];
                    $link = implode("/", $als) . "/" . $article->get_alias() . '/';

                    ?>
                    <a href="/<?php echo $link; ?>" style="background-image:url(<?php echo $val['photo']['src']; ?>)"
                       class="link-image">
                        <span class="title"><?php echo $article->get_title(); ?></span><!-- .title -->
                    </a>
                <?php
                }
                ?>
            </div>
            <!-- .col .col50 -->
            <div class="col col50">
                <h3><?php echo $this->settings['title'] ?></h3>
                <?php echo $this->settings["description"]; ?>
            </div>
            <!-- .col .col50 -->
        </div>
        <!-- .row-fluid -->
    </div>
    <!-- .widget-content -->
</div>
<!-- widget novosti -->