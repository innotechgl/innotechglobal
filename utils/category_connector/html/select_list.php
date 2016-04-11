<?php
// Load page
$engine->load_page('article_categories');

?>
<div class="article_categories_connector_form">
    <h2>Connect Places</h2>

    <div class="search">
        <span>Pretraga: </span><input type="text/javascript" value="" size="40"
                                      onkeyup="search_list.search_it(this.value);"/>
    </div>
    <!-- search -->
    <div class="scroll_list" id="article_categories_scroll_list">
        <?php
        // Load page
        //$engine->load_page('gallery_categories');
        $engine->article_categories->categories = array();
        $engine->article_categories->parents = array();
        $engine->article_categories->parents = $engine->article_categories->get_array(false);

        // Categorise
        $engine->article_categories->categorise();
        foreach ($engine->article_categories->categories as $key_cat => $val_cat) {
            $checked = '';
            if (is_array($val_cat)) {
                if (@in_array($val_cat['item']['id'], $categories_connection)) {
                    $checked = ' checked=checked ';
                }
                echo "<input type='checkbox' name='place_id[]' value='" . $val_cat['item']['id'] . "' '" . $checked . "' onclick=\"refresh_selected_cats();\" /> <span id=\"" . $val_cat['item']['id'] . "\">" . $val_cat['item']['name'] . "</span>";
            } else {
                switch ($val_cat) {
                    case "item_open":
                        echo "<li>";
                        break;
                    case "item_close":
                        echo "</li>";
                        break;
                    case "subcat_open":
                        echo "<ul>";
                        break;
                    case "subcat_close":
                        echo "</ul>";
                        break;
                }
            }
        }
        ?>
    </div>
</div><!-- gallery_connector_form -->
<script type="text/javascript" src="/utils/category_connector/js/search.js"></script>
<script type="text/javascript" src="/includes/js/utils/list_expander.js"></script>
<script type="text/javascript">
    var search_list = new search_class('article_categories_scroll_list', 'li', 'span');
    var list_expander = new list_expander_class('article_categories_scroll_list');
</script>