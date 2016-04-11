<?php
$article = $this->data;
?>
<section class="article-view">
    <scms:widget:innermenu>
        <article>
            <h1><?php echo $article->getTitle(); ?></h1>
            <div class="text"> <?php echo $article->getText(); ?> </div>
            <!-- .text -->
        </article>
</section>
<script>

</script>