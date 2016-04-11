<section class="articles-list">
    <div>
     <scms:widget:innermenu>
    </div>
    <h1><?php echo $this->data[0]->getElementFromData("category")->get_name(); ?></h1>

    <div class="row">
        <?php
        foreach ($this->data as $key => $article) {
            ?>
            <article class="col col-md-4 col-sm-6 col-lg-4 col-xs-12">
                <a href="<?php echo $article->getElementFromData("link"); ?>">
                    <span class="photo" style="background-image: url('<?php echo $article->getElementFromData("default_img_list"); ?>');"></span>
                    <span class="title"> <?php echo stripslashes($article->getTitle()); ?></span>
                    <span class="description"> <?php echo nl2br($article->getDescription()); ?></span>
                </a>
            </article>
            <?php
        }
        ?>
    </div>
</section>