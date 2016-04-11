<div class="widget <?php echo $this->settings['class_prefix']; ?>" id="widget_<?php echo $this->id; ?>">
    <h3><?php echo $this->settings['title']; ?></h3>

    <div class="widget-content">
        <div id="scrollbar_<?php echo $this->id; ?>" class="scrollbar_widget">
            <div class="scrollbar">
                <div class="track">
                    <div class="thumb">
                        <div class="end"></div>
                    </div>
                </div>
            </div>
            <div class="viewport">
                <div class="overview">
                    <ul>
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
                                if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                                    $als[] = $valAls['alias'];
                                }
                            }
                            $link = implode("/", $als) . "/" . $article->get_alias() . '/';

                            ?>
                            <li><a href="<?php echo $link; ?>" class="title"><?php echo $article->get_title(); ?></a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- scrollbar-mjesni-odbori -->
    </div>
    <!-- .widget-content -->
</div>
<!-- .widget .mjesni-odbori -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#scrollbar_<?php echo $this->id; ?>').tinyscrollbar();
    });
</script>