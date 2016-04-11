<div class="aktualna-zbivanja widget">
    <div class="center">
        <div class="widget-news widget">
            <h3><?php echo $this->settings['title']; ?></h3>

            <div class="row-fluid">
                <?php
                // getPath
                $engine->article_categories->founded_categories = array();
                $engine->article_categories->get_path($this->articles[0]['article']->get_categorie_id());

                $als = array();
                $fcats = $engine->article_categories->get_founded_categories();
                foreach ($fcats as $valAls) {
                    if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                        $als[] = $valAls['alias'];
                    }
                }
                $link = implode("/", $als) . "/" . $this->articles[0]['article']->get_alias() . '/';
                ?>
                <div class="col col40 img"><img src="<?php echo $this->articles[0]['photo']['src']; ?>"></div>
                <!-- .img -->
                <div class="col col60 texts">
                    <div class="main-text text"><a href="<?php echo $link; ?>"> <span
                                class="title"><?php echo $this->articles[0]['article']->get_date("d.m.Y.") . ": " . $this->articles[0]['article']->get_title(); ?></span><!-- .title -->
                            <p class="description"><?php echo $this->articles[0]['article']->get_description(); ?></p>
                        </a></div>
                    <!-- .main-text -->
                </div>
                <!-- .row-fluid -->
            </div>
            <div class="row-fluid">
                <?php
                for ($i = 1; $i < count($this->articles); $i++) {
                    // getPath
                    $engine->article_categories->founded_categories = array();
                    $engine->article_categories->get_path($this->articles[$i]['article']->get_categorie_id());
                    $als = array();
                    $fcats = $engine->article_categories->get_founded_categories();
                    foreach ($fcats as $valAls) {
                        if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                            $als[] = $valAls['alias'];
                        }
                    }
                    $link = implode("/", $als) . "/" . $this->articles[$i]['article']->get_alias() . '/';
                    ?>

                    <div class="text col col20">
                        <a href="<?php echo $link; ?>">
                            <div class="picture">
                                <img src="<?php echo $this->articles[$i]['photo']['src']; ?>">
                            </div>
                            <!-- .picture -->
                            <div class="date">
                                <?php echo $this->articles[0]['article']->get_date("d.m.Y."); ?>
                            </div>
                            <!-- .date -->
                            <span
                                class="icon icon-section section-title"><?php echo $this->articles[$i]['article']->get_title(); ?></span><!-- .icon-section -->
                            <!-- <span class="title"><?php echo $this->articles[$i]['article']->get_description(); ?></span><!-- .title -->
                        </a>
                    </div>
                    <!-- .text -->
                <?php } ?>
            </div>
            <!-- .texts -->
        </div>
        <!-- .row-fluid -->
    </div>
    <!-- .news -->
</div>
<!-- .center -->
</div>
<!-- .aktualna-zbivanja -->