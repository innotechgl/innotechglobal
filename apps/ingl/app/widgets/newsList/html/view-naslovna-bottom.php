<div class="col"> <div class="widget banner normal wt" id="widget_<?php echo $this->id; ?>">
                <h3><?php echo $this->settings["title"]; ?></h3>

                    <div class="widget-content">
                        <div class="item-container">
                            <?php
                            foreach($this->articles as $val){
                                $article = $val["article"];

                                ?>
                                <div class="item">
                                    <div class="info">
                                        <a href="<?php echo $article->getElementFromData("link"); ?>">
                                            <span class="title"><?php echo $article->getTitle(); ?></span>
                                            <span class="description"><?php echo $article->getDescription(); ?></span>
                                        </a>
                                    </div>
                                    <!-- .info -->
                                    <div class="image" style="background-image:url('<?php echo $article->getElementFromData("default_img_list"); ?>');"></div>
                                    <!-- .image -->
                                </div><!-- item -->
                                <?php

                            }

                            ?>

                        </div>
                        <!-- .item-container -->
                    </div>
                    <!-- .widget-content -->
                </div>
</div>