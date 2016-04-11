<div class="widget novosti <?php echo $this->settings['class_prefix']; ?>" id="widget_<?php echo $this->id; ?>">
    <h3><?php echo $this->settings['title'] ?></h3>

    <div class="news" id="news">
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
                    //print_r($valAls);
                    if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                        $als[] = $valAls['alias'];
                    }
                }
                // $link = "/articles/view/" . $article->get_categorie_id() . "/" . $article->get_id() . "/" . $engine->sef->filename_sef($article->get_title()) . ".html?menu_id=" . (int) $engine->sef->sef_params['menu_id'];
                $link = implode("/", $als) . "/" . $article->get_alias() . '/';

                //$link = '/articles/view/' . $article->get_categorie_id() . "/" . $article->get_id() . "/" . $engine->sef->filename_sef($article->get_title()) . ".html?menu_id=" . $this->settings['menu_id'];
                ?>
                <div class="news_item">
                    <div class="left"><a href="<?php echo $link; ?>"> <img
                                src="<?php echo $val['photo']['src']; ?>" <?php echo $thumbSize; ?> /></a></div>
                    <!-- left -->
                    <div class="right">
                        <span class="date"><?php echo $article->get_date("d.m.Y."); ?></span> <a class="title"
                                                                                                 href="<?php echo $link; ?>"><?php echo $article->get_title(); ?></a>
                    </div>
                    <div class="description">
                        <?php echo $article->get_description(); ?>
                    </div>
                    <!-- right -->
                    <div style="clear:both;"></div>
                </div>
                <!-- news_item -->
            <?php
            }
            ?>
        </div>
        <!-- .news-items -->
    </div>
    <!-- news -->
</div>
<!-- widget novosti -->
<script type="text/javascript" src="/widgets/newsList/js/newsAnimated.js"></script>
<script type="text/javascript">
    $(document.body).ready(function (e) {
        var f = new funcNewsList('widget_<?php echo $this->id; ?>', 4000);
        f.init();
    });
</script>