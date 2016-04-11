<?php
// separate
$this->articles = array_chunk($this->articles, 3);
?>

<div class="widget news <?php echo $this->settings["class_prefix"]; ?>">
    <h3><?php echo $this->settings["title"]; ?></h3>

    <div class="widget-content"><a class="a-left"> </a>
        <!-- .a-left -->
        <div class="banners-container">
            <?php
            for ($i = 0; $i < count($this->articles); $i++) {
                ?>
                <div class="banner row-fluid">
                    <?php
                    foreach ($this->articles[$i] as $key => $val) {
                        $article = $val["article"];
                        $thumbSize = '';
                        if (@$this->settings['thumbSize'] > 0) {
                            $thumbSize = 'width="' . $this->settings['thumbSize'] . '"';
                        }
                        // getPath
                        $engine->article_categories->founded_categories = array();
                        $engine->article_categories->get_path($article->getCategorieID());
                        $als = array();
                        $fcats = $engine->article_categories->get_founded_categories();
                        foreach ($fcats as $valAls) {
                            if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                                $als[] = $valAls['alias'];
                            }
                        }
                        $link = implode("/", $als) . "/" . $article->getAlias() . '/';
                        if ($val["photo"]["src"] !== "") {
                            $backgroundImage = "background-image:url(" . str_replace(" ", "%20", $val["photo"]["src"]) . ")";
                        }
                        ?>
                        <a class="normal" href="/<?php echo $link; ?>"> <span class="photo"
                                                                              style=" <?php echo $backgroundImage; ?>"> <span
                                    class="date"><span
                                        class="day"><?php echo date("d", strtotime($article->getDate())); ?></span><span
                                        class="month"><?php echo date("M", strtotime($article->getDate())); ?></span></span> <span
                                    class="title"><?php echo $article->getTitle(); ?></span> </span> </a>
                    <?php
                    }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
        <!-- .banners-container -->
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