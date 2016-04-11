<?php
$engine->load_page('article_categories');
$connected_gals = $engine->util_category_connector->get_array(false, '*', 'WHERE rel_id=' . (int)$rel_id . ' AND rel_page LIKE "' . $page . '"');
$places_ids = array();
foreach ($connected_gals as $key_g => $val_g) {
    $cat_ids[] = $val_g['categorie_id'];
}
if (count($gal_ids) > 0) {
    $photos = $engine->article_categories->get_array(false, '*', 'WHERE categorie_id IN (' . implode(",", $cat_ids) . ')');
    if (count($photos) > 0) {
        ?>
        <style type="text/css">
            .gallery { padding-bottom: 7px; text-align: left !important; }

            .photos { margin-left: 36px; width: 600px; margin-top: 30px; margin-bottom: 10px; }

            .photos .btn { float: left; width: 25px; background-color: #E81C66; display: block; height: 100px; text-align: center; line-height: 100px; cursor: pointer; color: white; }

            .photos .photos_cont { float: left; width: 550px; position: relative; height: 100px; overflow: hidden; }

            .photos .photos_container { position: absolute; left: 0; top: 0; width: 6000px; }

            .photos a.photo { margin-right: 0px; }

            .photo_text { margin-left: 36px; width: 600px; text-align: left; padding-top: 7px; }
        </style>
        <div class="article_categories" id="article_categories">
            <div class="photos">
                <div class="btn" id="btn_levo">
                    <
                </div>
                <!-- btn -->
                <div class="photos_cont">
                    <div class="photos_container" id="photos_container">
                        <?php
                        foreach ($photos as $key_p => $val_p) {
                            $dir = 'media/images/gallery/' . $val_p['categorie_id'];
                            $size = getimagesize($dir . "/thumb_" . $val_p['name']);
                            $title = $val_p['title'];
                            $img_src = '/' . $dir . '/thumb_' . $val_p['name'];
                            $img_src_big = '/' . $dir . '/' . $val_p['name'];
                            ?>
                            <a class="photo" href="<?php echo $img_src_big; ?>" id="photo" title="<?php echo $title; ?>"
                               rel="lightbox[]">
                                <img src="<?php echo $img_src; ?>" <?php echo $size[3]; ?> border="0" id="photo_img"/>
                            </a><!-- photo -->
                        <?php
                        }
                        ?>
                    </div>
                    <!-- photos_container -->
                </div>
                <!-- photos_cont -->
                <div class="btn" id="btn_desno">
                    >
                </div>
                <!-- btn -->
                <div style="clear: both;"></div>
            </div>
            <!-- photos -->
        </div><!-- gallery -->
        <script type="text/javascript">
            var photo_class = new Class({
                initialize: function () {
                    this.preview_cont = 'photos_container';
                    this.move_to = 0;
                    this.fx_preview = new Fx.Morph(this.preview_cont, {transition: Fx.Transitions.linear.easeIn});
                    $('gallery').addEvent('click', this.click_event.bind(this));
                },
                click_event: function (event) {
                    event.stop();
                    switch ($(event.target).get('id')) {
                        case "photo":
                            console.log('jeste');
                            break;
                        case "photo_img":
                            var href = $(event.target).getParent().get('href');
                            var title = $(event.target).getParent().get('title');
                            $('main_photo').set('src', href);
                            $('photo_text').set('text', title);
                            break;
                        case "btn_desno":
                            this.move_left();
                            break;
                        case "btn_levo":
                            this.move_right();
                            break;
                    }
                },
                move_left: function () {
                    if (this.move_to > 0) {
                        --this.move_to;
                        this.move_it();
                    }
                    else if (this.move_to < 0) {
                        this.move_to = 0;
                        this.move_it();
                    }
                },
                move_right: function () {
                    if (this.move_to < $$('.photo').length - 1) {
                        ++this.move_to;
                        this.move_it();
                    }
                },
                move_it: function () {
                    this.fx_preview.cancel();
                    var move = -(this.move_to * $$('.photo')[0].getSize().x);
                    this.fx_preview.start({'left': move});
                    //this.fx_preview.start();
                }
            });
            var photo = new photo_class();
        </script>
    <?php
    }
}
?>
