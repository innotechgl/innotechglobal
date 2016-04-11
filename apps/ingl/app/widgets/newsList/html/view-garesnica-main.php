<div class="widget mainNews clearfix <?php echo $this->settings['class_prefix']; ?>"
     id="widget_<?php echo $this->id; ?>"">
<div class="widget-content">
    <div class="latest">
        <?php

        $article = $this->articles[0]['article'];


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
        <div class="left"><img src="<?php echo $this->articles[0]['photo']['src']; ?>"/></div>
        <!-- .left -->
        <div class="right"><a href="/<?php echo $link; ?>" class="title"><?php echo $article->get_title(); ?></a>

            <div class="description"><a href="/<?php echo $link; ?>"><?php echo $article->get_description(); ?></a>
            </div>
        </div>
        <!-- .right -->
    </div>
    <!-- .latest -->
    <div class="news">
        <div class="news-items">
            <?php
            $j = 1;
            for ($i = 1; $i < count($this->articles); $i++) {
                $article = $this->articles[$i]['article'];
                $als = array();
                $fcats = $engine->article_categories->get_founded_categories();
                foreach ($fcats as $valAls) {
                    if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                        $als[] = $valAls['alias'];
                    }
                }
                $link = implode("/", $als) . "/" . $article->get_alias() . '/';
                if ($j == 1) {
                    ?>
                    <div class="news-item">
                <?php
                }
                ?>
                <article>
                    <div class="left"><img src="<?php echo $this->articles[$i]['photo']['src']; ?>"></div>
                    <!-- .left -->
                    <div class="right"><span class="date"><?php echo $article->get_date("d.m.Y."); ?></span> <a
                            href="/<?php echo $link; ?>" class="title"><?php echo $article->get_title(); ?></a></div>
                    <!-- .right -->
                </article>
                <?php
                if ($j == 2 || $i == (count($this->articles) - 1)) {
                    $j = 0;
                    ?>
                    </div>
                    <!-- .news-item -->
                <?php
                }
                ?>
                <?php
                $j++;
            }
            ?>
        </div>
        <!-- .news-items -->
        <div class="clearfix"></div>
    </div>
    <!-- .news -->
    <div class="news-items-pags clearfix"></div>
    <!-- .news-items-pags -->
</div>
<!-- .widget-content -->
</div>
<!-- .widget .mainNews -->
<script type="text/javascript" src="/widgets/newsList/js/newsAnimated.js"></script>
<script type="text/javascript">
    $(document.body).ready(function (e) {
        var f = new funcNewsList('widget_<?php echo $this->id; ?>', 4000);
        f.init();
    });
</script>