<div class="widget articles-list">
    <div class="arrow-left arrow"></div>

    <div class="articles-container">
        <section>
            <?php
            foreach($this->articles as $key=>$val){
                $article = $val["article"];
                $photo = $article->getElementFromData("photos");
            ?>
            <article class="article">
                <a href="<?php echo $article->getElementFromData("link"); ?>">
                    <span class="photo" style="background-image: url('<?php echo $photo[0]; ?>');"></span>
                    <span class="title"><?php echo $article->getTitle(); ?></span>
                </a>
            </article><!-- .article -->
            <?php
            }
            ?>
        </section>
    </div><!-- .articles-container -->
    <div class="arrow-right arrow"></div>
</div>
<script src="<?php echo $this->engine->settings->general->site."templates/granice-d/js/products-scroll.js" ?>"></script>