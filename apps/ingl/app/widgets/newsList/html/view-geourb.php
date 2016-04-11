<div class="widget news">
    <h3><?php echo $this->settings["title"]; ?></h3>

    <div class="widget-content"><a class="a-left"> </a>
        <!-- .a-left -->
        <div class="items-container">
            <div class="items">
                <?php
                foreach ($this->articles as $key => $val) {
                    $article = $val["article"];
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
                    if ($val["photo"]["src"] !== "") {
                        $backgroundImage = "background-image:url(" . $val["photo"]["src"] . ")";
                    }

                    ?>
                    <div class="item"><a href="<?php echo $link; ?>"> <span class="photo"
                                                                            style=" <?php echo $backgroundImage; ?>"></span>
                            <!-- .photo -->
                            <span class="title"><?php echo $article->get_title(); ?></span>
                            <!-- .title -->
                            <span class="text"><?php echo $article->get_description(); ?></span>
                            <!-- .text -->
                        </a></div>
                    <!-- .item -->
                <?php
                }
                ?>
            </div>
            <!-- .items -->
            <div class="clearfix"></div>
        </div>
        <!-- .items-container -->
        <a class="a-right"></a></div>
    <!-- .widget-content -->
</div>
<!-- .widget -->
<script>
    $(document).ready(function (e) {
        var m = function () {
            var main = this;
            this.active = 0;
            this.items = $(".item");
            this.init = function () {
                $("a.a-left").click(main.left);
                $("a.a-right").click(main.right);
            }
            this.left = function () {
                console.log("left");
                ++main.active;
                if (main.active > main.items.length - 1) {
                    main.active = main.items.length - 1;
                }
                main.move();
            }
            this.right = function () {
                console.log("right");
                --main.active;
                if (main.active < 0) {
                    main.active = 0;
                }
                main.move();
            }
            this.move = function () {
                var pos = $($(".item")[main.active]).position().left;
                var tr = "translate3d(-" + pos + "px,0,0)";
                $(".items").css({
                    "-webkit-transform": tr
                });
            }
        }
        var mf = new m();
        mf.init();
    });
</script>