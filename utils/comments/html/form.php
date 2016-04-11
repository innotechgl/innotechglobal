<?php
global $engine;
?>
<div class="comment_form" id="comment_form_<?php echo $this->util_id; ?>"
     style="position: absolute; display:none; padding: 10px;background-color: white;border:solid 2px black;z-index:999;">
    <div align="right"><a href="#" onclick="comments_<?php echo $this->util_id; ?>.hide_form();">X</a></div>
    <form method="post" enctype="multipart/form-data" action="<?php echo $link; ?>" id="comment_form">
        <table>
            <?php
            if ($engine->util_comments->registered_only == false) {
                ?>
                <tr>
                    <td>Ime</td>
                </tr>
                <tr>
                    <td><input type="text" value="" name="name" id="comment_<?php echo $this->util_id; ?>_name"/></td>
                </tr>
                <tr>
                    <td>mail</td>
                </tr>
                <tr>
                    <td><input type="text" value="" name="mail" id="comment_<?php echo $this->util_id; ?>_mail"/></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td>Komentar</td>
            </tr>
            <tr>
                <td>
                    <textarea cols="30" rows="10" name="" id="comment_<?php echo $this->util_id; ?>_comment"
                              class="mceNoEditor"></textarea>
                </td>
            </tr>
        </table>
        <button type="button" id="submit_comment" onclick="comments_<?php echo $this->util_id; ?>.submit_comment();">
            Pošalji
        </button>
    </form>
</div><!-- comment_form -->
