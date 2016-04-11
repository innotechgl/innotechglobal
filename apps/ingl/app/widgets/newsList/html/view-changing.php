<div class="widget <?php echo $this->settings['class_prefix']; ?> wt" id="widget_<?php echo $this->id; ?>">
    <h3><?php echo $this->settings['title']; ?></h3>

    <div class="widget-content">
        <div class="item-container">
            <?php
            foreach ($this->articles as $key => $val) {
                $article = $val['article'];
                $thumbSize = '';
                if (@$this->settings['thumbSize'] > 0) {
                    $thumbSize = 'width="' . $this->settings['thumbSize'] . '"';
                }

                $photo = "";
                if (count($article->getElementFromData("photos")) > 0){
                    $photo = $article->getElementFromData("photos")[0];
                }

                ?>
                <div class="item">
                    <div class="info">
                        <a href="<?php echo $article->getElementFromData("link"); ?>">
                            <span class="title"><?php echo stripslashes($article->getTitle()); ?></span>
                            <span class="description"><?php echo stripslashes($article->getdescription()); ?></span>
                        </a>
                    </div>
                    <!-- .info -->
                    <div class="image"
                         style="background-image:url('<?php echo $photo; ?>');"></div>
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
<!-- .widget -->
<script type="text/javascript" src="/widgets/newsList/js/simpleScroll.js"></script>
<script type="text/javascript">
    $(document.body).ready(function (e) {
        var m = new sScroll('widget_<?php echo $this->id; ?>');
        m.init();
    });
</script>