<?php
$article = $this->data;

$this->engine->parser->setOgSiteName($this->engine->settings->general->site);
$this->engine->parser->setOgDescription(htmlspecialchars($article->getDescription()));
$this->engine->parser->setOgTitle($article->getTitle());
$this->engine->parser->setOgUrl($this->engine->settings->general->site . $_SERVER['REQUEST_URI']);
$this->engine->parser->setOgType('article');

if (trim($article->getElementFromData("default_img_list")) !== "") {
    if (strpos($this->engine->settings->general->site,$article->getElementFromData("default_img_list"))){
        $this->engine->parser->setOgImage($article->getElementFromData("default_img_list"));
    }
    else {
        $this->engine->parser->setOgImage($this->engine->settings->general->site.$article->getElementFromData("default_img_list"));
    }

} else {
    $this->engine->parser->setOgImage($this->engine->settings->general->site."templates/".$this->engine->settings->general->template."/images/logo-color.png");
}

?>
<section class="article-view">
    <scms:widget:innermenu>
        <article>
            <h1><?php echo stripslashes($article->getTitle()); ?></h1>

            <div class="text"> <?php echo $article->getText(); ?> 
            
                <?php
            if ($article->getElementFromData("gallery")){
                ?>
            <div class="article-gallery">
                <?php
                foreach($article->getElementFromData("gallery") as $galleryItem){
                    ?>
                <a class="article-gallery-item" href="<?php echo $galleryItem; ?>" rel="thumb[]" style="background-image:url('<?php echo $galleryItem; ?>');"></a>
                    <?php
                }
                ?>
            </div>
                <?php
            }
            ?>
            </div>
            <!-- .text -->
            
            
        </article>
</section>
<script>

</script>