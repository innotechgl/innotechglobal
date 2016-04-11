<h2><?php echo $this->settings['title']; ?></h2>

<div class="team">
    <ul>
        <?php

        foreach($this->articles as $key=>$val) {
            $article = $val["article"];
            // var_dump($val->getElementFromData("default_img_list"));
            ?>
            <li><a href="#" class="ognjen-grba"> <span class="photo" style="background-image: url('<?php echo $article->getElementFromData("default_img_list"); ?>')"></span><!-- .photo -->
                    <div class="anime-team"></div>
                    <span class="name"><?php echo $article->getTitle(); ?></span><!-- .name -->
                    <span class="position"><?php echo $article->getDescription(); ?></span><!-- .position -->
                </a></li>
            <?php
        }
        ?>
    </ul>
</div>
<!-- .container -->