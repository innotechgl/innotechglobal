<div class="widget novosti <?php echo $this->settings['class_prefix']; ?>" id="widget_<?php echo $this->id; ?>">
    <h3><?php echo $this->settings['title']; ?></h3>

    <div class="widget-content">
        <div class="news">
            <div class="news-items">
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
                    <div class="news-item">
                        <div class="left">
                            <img src="<?php echo $this->articles[$key]['photo']['src']; ?>"/>
                        </div>
                        <!-- .left -->
                        <div class="right">
                            <span class="date"><?php echo $article->get_date("d.m.Y."); ?></span>
                            <a href="<?php echo $link; ?>"
                               class="title"><?php echo stripslashes($article->get_title()); ?></a>

                            <div class="description">
                                <?php echo $article->get_description(); ?>
                            </div>
                        </div>
                        <!-- .right -->
                    </div><!-- .news-item -->
                <?php
                }
                ?>
            </div>
            <!-- .news-items -->
        </div>
        <!-- .news -->
    </div>
    <!-- .widget-content -->
</div><!-- .novosti -->
<script type="text/javascript" src="/widgets/newsList/js/newsAnimated.js"></script>
<script type="text/javascript">
    $(document.body).ready(function (e) {
        var f = new funcNewsList('widget_<?php echo $this->id; ?>', 4000);
        f.init();
    });
</script>