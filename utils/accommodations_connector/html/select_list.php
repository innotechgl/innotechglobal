<?php
// Load page
$engine->load_page('accommodations');
?>
<div class="accommodations_connector_form">
    <h2>Vezana soba</h2>

    <div class="scroll_list" id="accommodations_scroll_list">
        <ul>
            <?php
            $rooms = $engine->accommodations->accommodationRooms->get_array(false, 'id, title', '', 'title', 'asc');

            $i = 0;
            foreach ($rooms as $key => $val) {
                $selected = '';
                if ($val['id'] == $room_id) {
                    $selected = 'checked';
                }
                ?>
                <li>
                    <input type="radio" value="<?php echo $val['id']; ?>" id="room_<?php echo $i; ?>"
                           name="room_id" <?php echo $selected; ?> />
                    <label for="room_<?php echo $i; ?>"><?php echo $val['title']; ?></label>
                </li>
                <?php
                $i++;
            }
            ?>
        </ul>
        <button id="clear-acc" type="button">Clear</button>
    </div>
</div><!-- accommodations_connector_form -->
<script>
    jQuery("#clear-acc").click(function (e) {
        jQuery("#accommodations_scroll_list input").each(function (index, element) {
            jQuery(element).removeAttr("checked");
        });
    });
</script>