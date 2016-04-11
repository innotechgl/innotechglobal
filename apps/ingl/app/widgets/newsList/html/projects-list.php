<h2><?php echo $this->settings['title']; ?></h2>

<div class="projects">
    <ul>
        <?php

        foreach($this->articles as $key=>$val) {
        $article = $val["article"];

        ?>
        <li><a href="#" class="project1"> <span class="photo" style="background-image: url('<?php echo $article->getElementFromData("default_img_list"); ?>')"></span><!-- .photo -->
                <div class="anime-project"></div><!-- div.project1 -->
                <span class="project"><?php echo $article->getDescription(); ?></span><!-- .project -->
                <span class="projectname"><?php echo $article->getTitle(); ?></span><!-- .projectname -->
            </a></li>
            <?php
        }
        ?>
    </ul>

</div>  <!-- .projects -->

<!-- .container -->