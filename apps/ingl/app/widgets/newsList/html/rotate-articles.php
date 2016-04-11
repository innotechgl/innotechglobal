<?php
$separated_articles = array();
if ($this->settings['separate_parts'] > 0) {
    $separated_articles = array_chunk($this->articles, $this->settings['separate_parts']);
} else {
    $separated_articles[] = $this->articles;
}
?>
<div class="widget novosti <?php echo $this->settings['class_prefix']; ?>" id="widget_<?php echo $this->id; ?>">
    <h3><?php echo $this->settings['title'] ?></h3>

    <div class="widget-content">
        <div class="articles-items">
            <?php
            for ($i = 0; $i < count($separated_articles); $i++) {
                ?>
                <div class="articles-part">
                    <?php
                    foreach ($separated_articles[$i] as $val) {
                        $article = $val['article'];
                        $photo = $val['photo'];
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
                        $link = implode("/", $als) . "/" . $article->get_alias() . '/';

                        ?>
                        <a class="article-item" href="<?php echo $link; ?>">
                    	<span class="image" style="background-image:url(<?php echo $photo['src']; ?>);">
                        	
                        </span><!-- .image -->
                        <span class="title">
                        	<?php echo $article->get_title(); ?>
                        </span><!-- .title -->
                        <span class="intro">
                        	<?php echo $article->get_description(); ?>
                        </span><!-- .intro -->
                        </a><!-- .article-item -->
                    <?php
                    }
                    ?>
                </div><!-- articles-part -->
            <?php
            }
            ?>
        </div>
        <!-- .articles-items -->
    </div>
    <!-- .widget-content -->
</div>
<!-- widget novosti -->
<script type="text/javascript" src="/widgets/newsList/js/articles-rotate.js"></script>
<script type="text/javascript">
    $(document.body).ready(function (e) {
        var f = new funcArticlesRotate('widget_<?php echo $this->id; ?>', 4000);
        f.init();
        console.log('start');
    });
</script>