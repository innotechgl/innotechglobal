<?php
if ($engine->users->get_id() <= 0) {
    $like_link = 'window.location="/users/login"';
    $dislike_link = 'window.location="/users/login"';
} else {
    $like_link = 'like.add(this,"like",' . $this->rel_id . ',"' . $this->rel_page . '"' . ');';
    $dislike_link = 'like.add(this,"dislike",' . $this->rel_id . ',"' . $this->rel_page . '"' . ');';
}
?>
<div id="like_btns">
    <a id="like" onclick='<?php echo $like_link; ?>'>
        Svidja mi se (<?php echo $this->rating_like; ?>)
    </a><!-- like -->
    <a id="dislike" onclick='<?php echo $dislike_link; ?>'>
        Ne svidja mi se (<?php echo $this->rating_dislike; ?>)
    </a><!-- dislike -->
</div><!-- like -->